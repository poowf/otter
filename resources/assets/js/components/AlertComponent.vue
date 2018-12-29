<template>
    <div>
        <div :class="['alert', ('alert-' + alertLevel) ,'alert-dismissible', 'text-left']">
            <button type="button" class="close" data-dismiss="alert"></button>
            <span>{{ alertMessage }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AlertComponent",
        props: [
            'alertLevel',
            'alertMessage',
        ]
    }
</script>