<template>
    <div>
        <li class="nav-item p-0" v-for="resourceName, index in allResourceNames">
            <a v-bind:href="`/otter/${resourceName}`" :class="['nav-link', 'pl-4', (currentRoute.includes(resourceName) ? 'active' :  '')]">{{ resourceName | beautify }}</a>
            <ul class="nav nav-tabs nav-child border-0 justify-content-center">
                <li class="nav-item"><a :class="['nav-link', 'pl-6', (currentRoute.includes(resourceName + '.index') ? 'active' :  '')]" v-bind:href="`/otter/${resourceName}`">View</a></li>
                <li class="nav-item"><a :class="['nav-link', 'pl-6', (currentRoute.includes(resourceName + '.create') ? 'active' :  '')]" v-bind:href="`/otter/${resourceName}/create`">Create</a></li>
            </ul>
        </li>
    </div>
</template>

<script>
    export default {
        name: "HeaderComponent",
        props: [
            'allResourceNames',
            'currentRoute'
        ],
        mounted() {
            console.log(this.currentRoute);
        },
    }
</script>


