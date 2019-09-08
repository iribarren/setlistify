<template>
    <tr v-if="show">
        <td>{{song.id}}</td>
        <td>{{song.songNameSetlist}}</td>
        <td>{{song.songName }}</td>
        <td>{{song.album }}</td>
        <td><img :src="song.cover" /></td>
        <td><input type="radio" @click="selectSong" v-bind:id="song.id" v-bind:name="'songs['+song.id+']'" v-bind:value="song.spotify_id" v-bind:checked="selected" /></td>
        <td><button class="btn btn-dark" @click.prevent="showMore" v-if="selected">{{button}}</button></a></td>

    </tr>
</template>

<script>
    import { EventBus } from '../event-bus.js';
    export default {
        name: "song",
        props: ['songinfo', 'index'],
        data() {
            return {
                song: this.songinfo,
                i: this.index,
                show: false,
                selected: false,
                button: '+'
            }
        },
        methods: {
            showMore: function () {
                if (this.button == '+') {
                    this.button = '-'
                    EventBus.$emit('show-all', this.song.id)
                } else {
                    this.button = '+'
                    EventBus.$emit('hide-all', this.song.id)
                }
            },
            selectSong: function() {
                this.selected = true
                this.button = '+'
                EventBus.$emit('songSelected', {'id':this.song.id,'spotify_id': this.song.spotify_id})
            }
        },
        beforeMount() {
            if (this.i == 0) {
                this.show = true
                this.selected = true
            }
            EventBus.$on('show-all', id => {
                if (id == this.song.id) {
                    this.show = true
                }
            });
            EventBus.$on('hide-all', id => {
                if (id == this.song.id) {
                    this.show = this.selected
                }
            });
            EventBus.$on('songSelected', data => {
                if (data.id == this.song.id) {
                    if (data.spotify_id != this.song.spotify_id) {
                        this.selected = false;
                    }
                    this.show = this.selected
                }
            });
        },
    }
</script>

<style scoped>

</style>