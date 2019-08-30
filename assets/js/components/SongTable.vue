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
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody v-for="(variations,index) in songs" v-bind:key="index">
            <song v-for="(song,i) in variations" v-bind:key="song.spotify_id"  v-bind:songinfo="song" v-bind:index="i"></song>
        </tbody>
    </table>    
</template>

<script>
    import Song from './Song.vue'
    export default {
        name: "song-table",
        components: {
            Song
        },
        props: {
            songsinfo: String,
        },
        data() {
            return {
                songs: JSON.parse(this.songsinfo)
            }
        }
    }
</script>

<style scoped>

</style>


