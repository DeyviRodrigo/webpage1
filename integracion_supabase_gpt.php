<?php

declare(strict_types=1);

require_once __DIR__ . '/adm/script/conex.php';

/**
 * Herramienta de integración con Supabase para importar noticias y banners.
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
    private string $bannerImageDir;
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
        $this->bannerImageDir = rtrim((string) ($config['banner_image_dir'] ?? __DIR__ . '/images/banner'), DIRECTORY_SEPARATOR);
        $this->defaultUserId  = (int) ($config['default_user_id'] ?? getenv('SUPABASE_DEFAULT_USER_ID') ?: 1);
        $this->httpTimeout    = (int) ($config['http_timeout'] ?? getenv('SUPABASE_HTTP_TIMEOUT') ?: 30);

        $this->fieldMap = [
            'id'                  => (string) ($config['field_map']['id'] ?? getenv('SUPABASE_FIELD_ID') ?? 'id'),
            'title'               => (string) ($config['field_map']['title'] ?? getenv('SUPABASE_FIELD_TITLE') ?? 'title'),
            'body'                => (string) ($config['field_map']['body'] ?? getenv('SUPABASE_FIELD_BODY') ?? 'body'),
            'image'               => (string) ($config['field_map']['image'] ?? getenv('SUPABASE_FIELD_IMAGE') ?? 'image_url'),
            'link'                => (string) ($config['field_map']['link'] ?? getenv('SUPABASE_FIELD_LINK') ?? 'link'),
            'published_at'        => (string) ($config['field_map']['published_at'] ?? getenv('SUPABASE_FIELD_PUBLISHED_AT') ?? 'published_at'),
            'banner_title'        => (string) ($config['field_map']['banner_title'] ?? getenv('SUPABASE_FIELD_BANNER_TITLE') ?? 'banner_title'),
            'banner_description'  => (string) ($config['field_map']['banner_description'] ?? getenv('SUPABASE_FIELD_BANNER_DESCRIPTION') ?? 'banner_description'),
            'banner_link'         => (string) ($config['field_map']['banner_link'] ?? getenv('SUPABASE_FIELD_BANNER_LINK') ?? 'banner_link'),
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
            'processed'        => 0,
            'created_news'     => 0,
            'created_banners'  => 0,
            'skipped'          => 0,
            'errors'           => [],
        ];

        foreach ($records as $record) {
            $summary['processed']++;

            try {
                $result = $this->syncRecord($record);
                $summary['created_news'] += $result['news_created'] ? 1 : 0;
                $summary['created_banners'] += $result['banner_created'] ? 1 : 0;

                if (!$result['news_created'] && !$result['banner_created']) {
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
     * @return array{news_created: bool, banner_created: bool}
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

        $bannerTitle = trim((string) ($record[$this->fieldMap['banner_title']] ?? ''));
        if ($bannerTitle === '') {
            $bannerTitle = $title;
        }

        $bannerDescription = trim((string) ($record[$this->fieldMap['banner_description']] ?? ''));
        if ($bannerDescription === '') {
            $bannerDescription = $this->truncateText($body, 200);
        }

        $bannerLink = trim((string) ($record[$this->fieldMap['banner_link']] ?? ''));
        if ($bannerLink === '') {
            $bannerLink = $link;
        }

        $needsNews   = !$this->newsExists($title, $publishedAt);
        $needsBanner = !$this->bannerExists($bannerTitle);

        if (!$needsNews && !$needsBanner) {
            return [
                'news_created'   => false,
                'banner_created' => false,
            ];
        }

        $downloadedImage = $this->downloadImageToTemp($imageUrl);

        try {
            $newsCreated = false;
            $bannerCreated = false;

            if ($needsNews) {
                $newsImageName = $this->storeImageFromTemp($downloadedImage['path'], $downloadedImage['extension'], $this->newsImageDir, 'news');
                $newsCreated   = $this->createNews($title, $body, $link, $newsImageName, $publishedAt);
            }

            if ($needsBanner) {
                $bannerImageName = $this->storeImageFromTemp($downloadedImage['path'], $downloadedImage['extension'], $this->bannerImageDir, 'banner');
                $bannerCreated   = $this->createBanner($bannerTitle, $bannerDescription, $bannerLink, $bannerImageName, $publishedAt);
            }
        } finally {
            if (is_file($downloadedImage['path'])) {
                @unlink($downloadedImage['path']);
            }
        }

        return [
            'news_created'   => $needsNews && $newsCreated,
            'banner_created' => $needsBanner && $bannerCreated,
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

    private function bannerExists(string $title): bool
    {
        $link = $this->connection->GetLink();
        $sql = 'SELECT idBanner FROM banner WHERE Titulo = ? LIMIT 1';

        $statement = $link->prepare($sql);
        if ($statement === false) {
            throw new RuntimeException('No fue posible preparar la verificación de banners existentes.');
        }

        $statement->bind_param('s', $title);
        if (!$statement->execute()) {
            $error = $statement->error;
            $statement->close();
            throw new RuntimeException('Error al verificar banners existentes: ' . $error);
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

    private function createBanner(string $title, string $description, string $link, ?string $imageName, string $publishedAt): bool
    {
        $sql = 'INSERT INTO banner (usersId, Titulo, Describir, Enlace, Imagen, estado, fecha) VALUES (?, ?, ?, ?, ?, 1, ?)';
        $statement = $this->connection->GetLink()->prepare($sql);

        if ($statement === false) {
            throw new RuntimeException('No fue posible preparar la inserción de banners.');
        }

        $linkParam = $link !== '' ? $link : null;
        $imageParam = $imageName;
        $statement->bind_param('isssss', $this->defaultUserId, $title, $description, $linkParam, $imageParam, $publishedAt);
        $success = $statement->execute();
        if (!$success) {
            $error = $statement->error;
            $statement->close();
            throw new RuntimeException('Error al insertar el banner: ' . $error);
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

    private function truncateText(string $text, int $length): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        if (function_exists('mb_strlen')) {
            if (mb_strlen($text, 'UTF-8') <= $length) {
                return $text;
            }

            return rtrim(mb_substr($text, 0, $length, 'UTF-8')) . '…';
        }

        if (strlen($text) <= $length) {
            return $text;
        }

        return rtrim(substr($text, 0, $length)) . '…';
    }
}

if (PHP_SAPI === 'cli' && realpath($_SERVER['argv'][0] ?? '') === __FILE__) {
    $connection = null;

    try {
        $connection = new MySQLcn();
        $integrator = new SupabaseNewsIntegrator($connection);
        $summary = $integrator->import();

        fwrite(STDOUT, sprintf(
            "Noticias procesadas: %d\nCreadas: %d\nBanners creados: %d\nOmitidas: %d\n",
            $summary['processed'],
            $summary['created_news'],
            $summary['created_banners'],
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
