<template>
    <div class="row justify-content-center" v-if="showOtherArtists">
        <div class="col-sm-6">
            <h3 class="text-center"> Artists that match your query</h3><button @click="showOtherArtists = false">Close</button>
            <ul class="list-group-horizontal">
                <li v-for="artist in allArtists" class="list-group-item list-group-item-dark text-center">
                    <a :href="artist.url">
                        {{ artist.name }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../event-bus.js';
    export default {
        name: "other-artists",
        props: {
            artists: String,
        },
        data() {
            return {
                allArtists: JSON.parse(this.artists),
                showOtherArtists: false,
            }
        },
        created() {
            EventBus.$on('show-other-artists', this.show)
        },
        methods: {
            show: function () {
                this.showOtherArtists = true
            }
        },
    }
</script>

<style scoped></style>
