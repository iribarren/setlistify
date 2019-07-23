<template>
    <tbody :id="num" v-if="show">
        <tr v-for="song in songVersions">
            <td>{{num}}</td>
            <td>{{name}}</td>
            <td>{{ song.name }}</td>
            <td>{{ song.album }}</td>
            <td><img :src="song.album_cover" /></td>
            <td><input type="radio" :id="song.id" :name="'songs['+num+']'" :value="song.id" /></td>
        </tr>
    </tbody>
</template>

<script>
    export default {
        name: "song",
        props: ['versions','songNumber','songName'],
        data() {
            return {
                songVersions: JSON.parse(this.versions),
                num: this.songNumber,
                name: this.songName
            }
        },
        computed: {
            show () {
                return this.songVersions.length>0
            }
        }
    }
</script>

<style scoped>

</style>