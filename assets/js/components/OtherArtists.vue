<template>
    <div class="modal" id="other-artists">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Other artists that match your query</h5>
                    <button type="button" data-dismiss="modal" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li v-for="artist in allArtists" class="list-group-item list-group-item-dark text-center">
                            <a :href="artist.url">
                                {{ artist.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "other-artists",
        props: {
            artists: String,
        },
        data() {
            return {
                allArtists: JSON.parse(this.artists),
            }
        },
    }
</script>

<style scoped></style>
