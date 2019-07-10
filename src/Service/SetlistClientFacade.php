<?php

namespace App\Service;

use SetlistClient\Client;

class SetlistClientFacade extends Client
{
    
    protected function runRequest($path, $method = 'GET', $data = '')
    {
        $result = parent::runRequest($path,$method,$data);
        
        return $this->getResponseFormat() == 'application/json' ? json_decode($result,true) : $result;
    }
    
    public function searchSetlistsForArtist($artistName)
    {
        $result = $this->artist->search("","",$artistName);
        
        $artist = $result['total'] == 1 ? $result['artist'][0] : $result['artist'][0]; //??
        
        $setlists = $this->setlist->search($artist['mbid']);

        if (isset($setlists['setlist'])) {
            foreach ($setlists['setlist'] as &$setlist) {
                $songs = $this->getSetlistSongs($setlist);
                $setlist['songCount'] = count($songs);
            }
        } else {
            return []; 
        }
        
        return $setlists;
    }
    
    public function searchLastSetlistForArtist($artistName)
    {
        $result = $this->artist->search("","",$artistName);
        $songs = [];
        
        if (isset($result['code']) ?? $result['code'] == '404') {
            return []; 
        }
        
        $artist = $result['total'] == 1 ? $result['artist'][0] : $result['artist'][0];
        $setlists = $this->setlist->search($artist['mbid']);
        
        if (isset($setlists['setlist'])) {
            $setlistCount = count($setlists['setlist']);
            $songCount = 0;
            $i = 0;
            while ($songCount == 0 && $i < $setlistCount){
                $setlist = $setlists['setlist'][$i];
                $songs = $this->getSetlistSongs($setlist);
                $songCount = count($songs);
                $i++;
            }
        } else {
            return []; //return empty array or throw error
        }
        
        return [$setlist,$songs];
    }
    
    public function getSetlistSongs($setlist) 
    {
        $songs = [];
        foreach ($setlist['sets']['set'] as $set) {
            foreach ($set['song'] as $song) {
                $songs[] = $song; 
            }
        }
        
        return $songs;
    }
}