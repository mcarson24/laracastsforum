<script>
	import Favorite from './Favorite.vue';

	export default {
		props: ['attributes'],
		components: { Favorite },
		data() {
			return {
				editing: false,
				body: this.attributes.body
			};
		},
		methods: {
			update() {
				axios.patch('/replies/' + this.attributes.id, {
					body: this.body
				});
				flash('Updated your reply!');
				this.editing = false;
			},
			destroy() {
				axios.delete('/replies/' + this.attributes.id);

				$(this.$el).fadeOut(300, () => {
					flash('Deleted your reply!');
				});

			}
		}
	}
</script>