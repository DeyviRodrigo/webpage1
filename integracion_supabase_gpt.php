<?php

declare(strict_types=1);

require_once __DIR__ . '/adm/script/conex.php';

if (!function_exists('loadEnvFile')) {
    /**
     * Carga pares clave/valor desde un archivo .env simple y los inyecta en el entorno.
     */
    function loadEnvFile(string $path): void
    {
        if (!is_file($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            if ($key === '') {
                continue;
            }

            $value = trim($parts[1]);
            if ($value !== '') {
                $firstChar = substr($value, 0, 1);
                $lastChar  = substr($value, -1);
                if (in_array($firstChar, [chr(34), chr(39)], true) && $firstChar === $lastChar) {
                    $value = substr($value, 1, -1);
                }
            }

            if (getenv($key) === false) {
                putenv($key . '=' . $value);
            }

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

loadEnvFile(__DIR__ . '/.env');

/**
 * Herramienta de integración con Supabase para importar noticias.
 */
class SupabaseNewsIntegrator
{
    private const DEFAULT_NEWS_SELECT = '*';

    private MySQLcn $connection;
    private string $supabaseUrl;
    private string $supabaseKey;
    private string $newsTable;
    private string $newsSelect;
    private string $newsImageDir;
    private int $defaultUserId;
    private int $httpTimeout;

    /**
     * @var array<string, string>
     */
    private array $fieldMap;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(MySQLcn $connection, array $config = [])
    {
        $this->connection = $connection;
        $this->supabaseUrl = rtrim((string) ($config['supabase_url'] ?? getenv('SUPABASE_URL') ?: ''), '/');
        $this->supabaseKey = trim((string) ($config['supabase_key'] ?? getenv('SUPABASE_KEY') ?: ''));
        $this->newsTable   = trim((string) ($config['supabase_news_table'] ?? getenv('SUPABASE_NEWS_TABLE') ?: 'news'));
        $this->newsSelect  = (string) ($config['supabase_news_select'] ?? getenv('SUPABASE_NEWS_SELECT') ?: self::DEFAULT_NEWS_SELECT);
        $this->newsImageDir   = rtrim((string) ($config['news_image_dir'] ?? __DIR__ . '/images/news'), DIRECTORY_SEPARATOR);
        $this->defaultUserId  = (int) ($config['default_user_id'] ?? getenv('SUPABASE_DEFAULT_USER_ID') ?: 1);
        $this->httpTimeout    = (int) ($config['http_timeout'] ?? getenv('SUPABASE_HTTP_TIMEOUT') ?: 30);

        $this->fieldMap = [
            'id'           => (string) ($config['field_map']['id'] ?? getenv('SUPABASE_FIELD_ID') ?? 'id'),
            'title'        => (string) ($config['field_map']['title'] ?? getenv('SUPABASE_FIELD_TITLE') ?? 'title'),
            'body'         => (string) ($config['field_map']['body'] ?? getenv('SUPABASE_FIELD_BODY') ?? 'body'),
            'image'        => (string) ($config['field_map']['image'] ?? getenv('SUPABASE_FIELD_IMAGE') ?? 'image_url'),
            'link'         => (string) ($config['field_map']['link'] ?? getenv('SUPABASE_FIELD_LINK') ?? 'link'),
            'published_at' => (string) ($config['field_map']['published_at'] ?? getenv('SUPABASE_FIELD_PUBLISHED_AT') ?? 'published_at'),
        ];

        if ($this->supabaseUrl === '' || $this->supabaseKey === '') {
            throw new InvalidArgumentException('Debe configurar SUPABASE_URL y SUPABASE_KEY para ejecutar la sincronización.');
        }
    }

    /**
     * Importa todas las noticias disponibles en Supabase.
     *
     * @return array<string, mixed>
     */
    public function import(): array
    {
        $records = $this->fetchNewsFromSupabase();

        $summary = [
            'processed'    => 0,
            'created_news' => 0,
            'skipped'      => 0,
            'errors'       => [],
        ];

        foreach ($records as $record) {
            $summary['processed']++;

            try {
                $result = $this->syncRecord($record);
                $summary['created_news'] += $result['news_created'] ? 1 : 0;

                if (!$result['news_created']) {
                    $summary['skipped']++;
                }
            } catch (Throwable $throwable) {
                $summary['errors'][] = [
                    'record'  => $record,
                    'message' => $throwable->getMessage(),
                ];
            }
        }

        return $summary;
    }

    /**
     * @param array<string, mixed> $record
     *
     * @return array{news_created: bool}
     */
    private function syncRecord(array $record): array
    {
        $title = trim((string) ($record[$this->fieldMap['title']] ?? ''));
        $body  = (string) ($record[$this->fieldMap['body']] ?? '');

        if ($title === '') {
            throw new RuntimeException('El registro obtenido de Supabase no contiene un título válido.');
        }

        $imageUrl = trim((string) ($record[$this->fieldMap['image']] ?? ''));
        if ($imageUrl === '') {
            throw new RuntimeException(sprintf('La noticia "%s" no incluye una URL de imagen válida.', $title));
        }

        $link = trim((string) ($record[$this->fieldMap['link']] ?? ''));
        $publishedAt = $this->resolvePublishedAt($record[$this->fieldMap['published_at']] ?? null);

        $needsNews   = !$this->newsExists($title, $publishedAt);
        if (!$needsNews) {
            return [
                'news_created' => false,
            ];
        }

        $downloadedImage = $this->downloadImageToTemp($imageUrl);

        try {
            $newsImageName = $this->storeImageFromTemp($downloadedImage['path'], $downloadedImage['extension'], $this->newsImageDir, 'news');
            $newsCreated   = $this->createNews($title, $body, $link, $newsImageName, $publishedAt);
        } finally {
            if (is_file($downloadedImage['path'])) {
                @unlink($downloadedImage['path']);
            }
        }

        return [
            'news_created' => $newsCreated,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchNewsFromSupabase(): array
    {
        $query = http_build_query(['select' => $this->newsSelect]);
        $endpoint = sprintf('%s/rest/v1/%s?%s', $this->supabaseUrl, rawurlencode($this->newsTable), $query);

        $curl = curl_init($endpoint);
        if ($curl === false) {
            throw new RuntimeException('No fue posible inicializar la conexión cURL.');
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => $this->httpTimeout,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                'apikey: ' . $this->supabaseKey,
                'Authorization: Bearer ' . $this->supabaseKey,
                'Prefer: return=representation',
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($response === false) {
            throw new RuntimeException('Error ejecutando la solicitud a Supabase: ' . $curlError);
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new RuntimeException(sprintf('Supabase respondió con un código HTTP inesperado: %d. Respuesta: %s', $httpCode, $response));
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            throw new RuntimeException('La respuesta de Supabase no se pudo decodificar como JSON.');
        }

        return $data;
    }

    /**
     * @param string|null $publishedAt
     */
    private function resolvePublishedAt($publishedAt): string
    {
        if (is_string($publishedAt) && $publishedAt !== '') {
            try {
                $dateTime = new DateTimeImmutable($publishedAt);
                return $dateTime->format('Y-m-d H:i:s');
            } catch (Exception $exception) {
                // Ignorar y continuar con la fecha actual.
            }
        }

        return date('Y-m-d H:i:s');
    }

    private function newsExists(string $title, string $publishedAt): bool
    {
        $link = $this->connection->GetLink();
        $sql = 'SELECT idNoticia FROM noticias WHERE titulo = ? AND fecha = ? LIMIT 1';

        $statement = $link->prepare($sql);
        if ($statement === false) {
            throw new RuntimeException('No fue posible preparar la verificación de noticias existentes.');
        }

        $statement->bind_param('ss', $title, $publishedAt);
        if (!$statement->execute()) {
            $error = $statement->error;
            $statement->close();
            throw new RuntimeException('Error al verificar noticias existentes: ' . $error);
        }

        $statement->store_result();
        $exists = $statement->num_rows > 0;
        $statement->close();

        return $exists;
    }

    private function createNews(string $title, string $body, string $link, ?string $imageName, string $publishedAt): bool
    {
        $sql = 'INSERT INTO noticias (usersId, titulo, cuerpo, imagen, enlace, estado, fecha) VALUES (?, ?, ?, ?, ?, 1, ?)';
        $statement = $this->connection->GetLink()->prepare($sql);

        if ($statement === false) {
            throw new RuntimeException('No fue posible preparar la inserción de noticias.');
        }

        $linkParam = $link !== '' ? $link : null;
        $imageParam = $imageName;
        $statement->bind_param('isssss', $this->defaultUserId, $title, $body, $imageParam, $linkParam, $publishedAt);
        $success = $statement->execute();
        if (!$success) {
            $error = $statement->error;
            $statement->close();
            throw new RuntimeException('Error al insertar la noticia: ' . $error);
        }

        $statement->close();

        return true;
    }

    /**
     * @return array{path: string, extension: string}
     */
    private function downloadImageToTemp(string $url): array
    {
        $curl = curl_init($url);
        if ($curl === false) {
            throw new RuntimeException('No fue posible inicializar la descarga de la imagen.');
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => $this->httpTimeout,
        ]);

        $binaryData = curl_exec($curl);
        $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $contentType = (string) curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($binaryData === false) {
            throw new RuntimeException('Error al descargar la imagen: ' . $curlError);
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new RuntimeException(sprintf('La descarga de la imagen respondió con código HTTP %d.', $httpCode));
        }

        $extension = $this->resolveImageExtension($url, $contentType);

        $tempPath = tempnam(sys_get_temp_dir(), 'supabase_img_');
        if ($tempPath === false) {
            throw new RuntimeException('No fue posible crear un archivo temporal para la imagen.');
        }

        if (file_put_contents($tempPath, $binaryData) === false) {
            @unlink($tempPath);
            throw new RuntimeException('No fue posible guardar la imagen descargada.');
        }

        return [
            'path'      => $tempPath,
            'extension' => $extension,
        ];
    }

    private function storeImageFromTemp(string $tempPath, string $extension, string $directory, string $prefix): string
    {
        $this->ensureDirectoryExists($directory);

        try {
            $random = bin2hex(random_bytes(4));
        } catch (Exception $exception) {
            $random = (string) random_int(100000, 999999);
        }

        $fileName = sprintf('%s_%s_%s.%s', $prefix, date('YmdHis'), $random, $extension);
        $destination = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

        if (!copy($tempPath, $destination)) {
            throw new RuntimeException(sprintf('No fue posible copiar la imagen al directorio %s.', $directory));
        }

        return $fileName;
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (is_dir($directory)) {
            return;
        }

        if (!mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('No fue posible crear el directorio %s.', $directory));
        }
    }

    private function resolveImageExtension(string $url, string $contentType): string
    {
        $knownTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];

        $contentType = strtolower(trim($contentType));
        if (isset($knownTypes[$contentType])) {
            return $knownTypes[$contentType];
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (is_string($path)) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (is_string($extension) && $extension !== '') {
                return strtolower($extension);
            }
        }

        return 'jpg';
    }

}

if (PHP_SAPI === 'cli' && realpath($_SERVER['argv'][0] ?? '') === __FILE__) {
    $connection = null;

    try {
        $connection = new MySQLcn();
        $integrator = new SupabaseNewsIntegrator($connection);
        $summary = $integrator->import();

        fwrite(STDOUT, sprintf(
            "Noticias procesadas: %d\nCreadas: %d\nOmitidas: %d\n",
            $summary['processed'],
            $summary['created_news'],
            $summary['skipped']
        ));

        if (!empty($summary['errors'])) {
            fwrite(STDERR, "Errores encontrados:\n");
            foreach ($summary['errors'] as $error) {
                fwrite(STDERR, '- ' . ($error['message'] ?? 'Error desconocido') . "\n");
            }
        }
    } catch (Throwable $throwable) {
        fwrite(STDERR, 'Error en la integración con Supabase: ' . $throwable->getMessage() . "\n");
        exit(1);
    } finally {
        if ($connection instanceof MySQLcn) {
            $connection->Close();
        }
    }
}
