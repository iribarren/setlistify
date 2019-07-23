<template>
    <table class="table table-striped table-light table-hover">
        <thead>
            <tr>
                <td>#</td>
                <td>Setlist song</td>
                <td>Song</td>
                <td>Album</td>
                <td>Cover</td>
                <td>Add</td>
            </tr>
        </thead>
        <!--{% for songs in songsSpotifyInfo %}-->
        <song v-for="song in songs" versions="{{ song|json_encode}}" song-number="{{ loop.index }}" song-name="{{ setlistSongs[loop.index0].name }}"></song>
        <!--{% endfor %}-->
    </table>    
</template>

<script>
    import Song from './Song.vue'
    export default {
        name: "song-table",
        props: ['songsInfo'],
        data() {
            return {
                songs: JSON.parse(this.songsInfo),
            }
        },
    }
</script>

<style scoped>

</style>


