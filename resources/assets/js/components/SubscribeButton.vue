<template>
	<button :class="classes" @click="toggleSubscription" v-text="buttonText"></button>
</template>

<script>
	export default {
		props: ['active'],
		data() {
			return {
				isActive: this.active
			}
		},
		methods: {
			toggleSubscription() {
				let requestType = this.isActive ? 'delete' : 'post';

				axios[requestType](location.pathname + '/subscriptions')
				 	 .then(this.isActive = !this.isActive);	
		 	}
		},
		computed: {
			classes() {
				return ['btn', this.isActive ? 'btn-primary' : 'btn-default'];
			},
			buttonText() {
				return this.isActive ? 'Unsubscribe' : 'Subscribe';
			}
		}
	}
</script>