<template>
    <span v-if="Array.isArray(field.value)">{{field.value}}</span>
    <span v-else>{{ field.options.find(item => item['value'] == field.value).label }} {{this.label}}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
}
</script>