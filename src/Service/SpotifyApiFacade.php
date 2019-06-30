<?php

namespace App\Service;

use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiFacade extends SpotifyWebAPI
{
    public function searchSong($song, $artist = '') 
    {
        $searchQuery = '"'.$song.'"';
        if ($artist != '') {
            $searchQuery .="+artist:".$artist;
        }
        $result = $this->search($searchQuery,'track');
        $songs = [];

        foreach ($result->tracks->items as $track) {
            $album = $track->album;
            $artist = $track->artists[0];
            $songs[] = [
                'id' => $track->id,
                'name' => $track->name,
                'artist' => $artist->name,
                'album' => $album->name,
                'album_cover' => $album->images[2]->url,
                'album_release_date' => $album->release_date,

            ];
        }

        return $songs;
    }
    
    public function searchSongsFullInfo($songs, $artist = '')
    {
        $songsInfo = [];
        foreach ($songs as $song) {
            //we search for the song with the artist name, if it fails it will look if its a cover
            $songs = $this->searchSong($song['name'],$artist);
            $songsInfo[] = $songs;
        }
        
        return $songsInfo;
    }
    
    public function addPlaylistLucky($name, $songs,$artist) 
    {
        $result = $this->createPlaylist(['name' => $name]);
        
        $playlistId = $result->id;
        
        foreach ($songs as $song) {
            $spotifySong = $this->searchSong($song['name'], $artist);
            if (!empty($spotifySong)) {
                $this->addPlaylistTracks($playlistId, $spotifySong[0]['id']);
            }
        }
        
        return $result;
    }
    
    public function addPlaylist($name, $songs) 
    {
        $result = $this->createPlaylist(['name' => $name]);
        
        $playlistId = $result->id;
        
        foreach ($songs as $song) {
            $this->addPlaylistTracks($playlistId, $song);
        }
        
        return $result;
    }

    public function getArtistInfo($artistName) 
    {
        $result = $this->search($artistName,'artist');
                
        return $result;
    }
}