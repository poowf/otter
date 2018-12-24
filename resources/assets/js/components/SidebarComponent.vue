<template>
    <div>
        <li class="nav-item" v-for="resourceName, index in allResourceNames">
            <a v-bind:href="`#${resourceName}-menu`" data-toggle="collapse" class="nav-link dropdown-toggle pl-4" aria-expanded="false">{{ resourceName | capitalize }}</a>
            <ul v-bind:id="`${resourceName}-menu`" class="nav nav-tabs border-0 justify-content-center collapse">
                <li class="nav-item"><a class="nav-link pl-6" v-bind:href="`/otter/${resourceName}`">View</a></li>
                <li class="nav-item"><a class="nav-link pl-6" v-bind:href="`/otter/${resourceName}/create`">Create</a></li>
            </ul>
        </li>
    </div>
</template>

<script>
    export default {
        name: "SidebarComponent",
        props: [
            'allResourceNames'
        ],
        mounted() {
            console.log('Component mounted.')
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
    }
</script>