<?php

namespace App\Services;

class SpotifyService
{
    protected array $playlistMap = [
        'K-Pop'       => '37i9dQZF1DX9tPFwDMOaN1',
        'Pop'         => '37i9dQZF1DXcBWIGoYBM5M',
        'Rock'        => '37i9dQZF1DWXRqgorJj26U',
        'Jazz'        => '37i9dQZF1DXbITWG1ZJKYt',
        'EDM'         => '37i9dQZF1DX4dyzvuaRJ0n',
        'Hip-Hop'     => '37i9dQZF1DX0XUsuxWHRQd',
        'R&B'         => '37i9dQZF1DX4SBhb3fqCJd',
        'Dangdut'     => '37i9dQZF1DX2RTtHCBBtGn',
        'Indie'       => '37i9dQZF1DXdbXrPNafg9d',
        'Classical'   => '37i9dQZF1DWWEJlAGA9gs0',
        'Festival'    => '37i9dQZF1DXcBWIGoYBM5M',
        'Fan Meeting' => '37i9dQZF1DX9tPFwDMOaN1',
        'Concert'     => '37i9dQZF1DXcBWIGoYBM5M',
        'Music'       => '37i9dQZF1DXcBWIGoYBM5M',
        'Sports'      => '37i9dQZF1DWZq91oLsHZvy',
        'default'     => '37i9dQZF1DXcBWIGoYBM5M',
    ];

    public function getTracksForEvent(
        ?string $artist,
        ?string $category,
        int $limit = 10,
        ?string $playlistId = null
    ): ?array {
        if (empty($playlistId)) {
            return null;
        }

        $input = trim($playlistId);
        $input = preg_replace('#/intl-[a-z]+/#', '/', $input);

        if (str_contains($input, '/track/')) {
            preg_match('/track\/([a-zA-Z0-9]+)/', $input, $matches);
            return [['embed_type' => 'track', 'embed_id' => $matches[1] ?? $input]];
        }

        if (str_contains($input, '/album/')) {
            preg_match('/album\/([a-zA-Z0-9]+)/', $input, $matches);
            return [['embed_type' => 'album', 'embed_id' => $matches[1] ?? $input]];
        }

        if (str_contains($input, '/playlist/')) {
            preg_match('/playlist\/([a-zA-Z0-9]+)/', $input, $matches);
            return [['embed_type' => 'playlist', 'embed_id' => $matches[1] ?? $input]];
        }

        return [['embed_type' => 'playlist', 'embed_id' => $input]];
    }

    public static function formatDuration(int $ms): string
    {
        $seconds = (int) ($ms / 1000);
        return sprintf('%d:%02d', intdiv($seconds, 60), $seconds % 60);
    }
}