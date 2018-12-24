<template>
    <div>
        <div class="row row-cards row-deck">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ resourceName }}</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap">
                            <thead>
                                <tr>
                                    <th v-for="tableName, tableKey in resourceFields">{{ tableKey }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="resource, index in resourceData">
                                    <td v-for="fieldType, fieldKey in resourceFields">{{ resource[`${fieldKey}`] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TableComponent",
        props: [
            'resourceName',
            'resourceFields',
        ],
        data() {
            return {
                resourceData: [],
            }
        },
        watch: {
            resourceName() {
                console.log("changed");
                // if (this.resourceName) {
                //     this.fetchResourceIndex();
                // }
            }
        },
        created() {
            this.fetchResourceIndex();
            console.log(this.resourceFields);
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            fetchResourceIndex() {
                axios.get(`/api/otter/${this.resourceName}`)
                    .then(response=>{
                        this.resourceData = response.data.data;
                        console.log(this.resourceData);
                    })
                    .catch(e => {
                        this.error = `Could not retrieve ${this.resourceName}. Server error.`;
                    })
                    .finally(() => {
                    });
            },
        }
    }
</script>