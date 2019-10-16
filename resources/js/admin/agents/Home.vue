<template>
    <div class="page_wrap_vue pa-3">
        <h2>AGENTS HOME PAGE</h2>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Alias voluptatum, maiores repellendus, quia voluptas nostrum consequatur quos iste molestiae voluptates sapiente dolore voluptatibus commodi odio facere nemo sit reprehenderit incidunt.</p>
    </div>
</template>

<script>
    export default {
        // inject: ['theme'],
        mounted() {

            const self = this;

            self.$store.commit('setBreadcrumbs',[
                {label:'Dashboard',name:''}
            ]);
        }
    }
</script>