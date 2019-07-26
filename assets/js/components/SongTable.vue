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
        <tbody v-for="(variations,index) in songs" v-bind:key="index">
            <song v-for="song in variations" v-bind:key="song.spotify_id"  v-bind:song="song"></song>
        </tbody>
    </table>    
</template>

<script>
    export default {
        name: "song-table",
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


