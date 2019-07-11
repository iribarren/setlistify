<template>
    <nav aria-label="Page navigation example" >
        <ul class="pagination justify-content-center ">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: "pager",
    }
</script>

<style scoped>
    .pagination > li > a
    {
        background-color: white;
        color: #5A4181;
    }

    .pagination > li > a:focus,
    .pagination > li > a:hover,
    .pagination > li > span:focus,
    .pagination > li > span:hover
    {
        color: #5a5a5a;
        background-color: #eee;

    }

    .pagination > .active > a
    {
        color: darkgrey;
        background-color: #5A4181 !Important;
    }

    .pagination > .active > a:hover
    {
        background-color: #5A4181 !Important;

    }


</style>
