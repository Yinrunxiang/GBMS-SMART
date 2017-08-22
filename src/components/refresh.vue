<template>
	<div></div>
</template>
<script>
export default {
  created() {
    console.log(this.$route.query.name)
    if (this.$route.query.name) {
      router.replace({ name: this.$route.query.name })
    } else {
      console.log('refresh fail')
    }
  }
}
</script>