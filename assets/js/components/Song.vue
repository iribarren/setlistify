<template>
    <tr>
        <td>{{song.id}}</td>
        <td>{{song.songNameSetlist}}</td>
        <td>{{song.songName }}</td>
        <td>{{song.album }}</td>
        <td><img :src="song.album_cover" /></td>
        <td><input type="radio" :id="song.id" :name="'songs['+num+']'" :value="song.id" /></td>
    </tr>
</template>

<script>
    export default {
        name: "song",
        props: ['songinfo'],
        data() {
            return {
                song = this.songinfo
            }
        },
        /*
        computed: {
            show () {
                return this.songVersions.length>0
            }
        }
        */
    }
</script>

<style scoped>

</style>