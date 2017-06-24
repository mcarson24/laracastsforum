<template>
	<div>
		<div :id="'reply-' + this.id" class="panel-heading">
			<div v-if="editing">
				<div class="form-group">
					<textarea class="form-control" v-model="body"></textarea>
				</div>
				<button class="btn btn-default btn-xs" @click="editing = false">Cancel</button>
				<button class="btn btn-link btn-xs" @click="update">Update</button>
			</div>
			<div v-else v-text="body"></div>
		</div>
		<div class="panel-body">
			<div class="level">
				<h5 class="flex">
				    by<a :href="'/profiles/' + data.owner.id" v-text="data.owner.name"></a>
				    <span class="reply-time">{{ data.created_at }}</span>
				</h5>
			    	<!-- @if (auth()->check())
				    	<favorite :reply="{{ $reply }}"></favorite>
			    	@else
				    	<span class="glyphicon glyphicon-heart mr-quarter"></span>
			    		<span>{{ $reply->favoritesCount }}</span>
			    	@endif -->
		    </div>
		</div>
		<!-- @can ('update', $reply) -->
			<div class="panel-footer level">
				<button class="btn btn-default btn-xs mr-1" @click="editing = !editing">Edit</button>
				<button class="btn btn-default btn-xs" @click="destroy">Delete</button>
			</div>
		<!-- @endcan -->
	</div>
</template>

<script>
	import Favorite from './Favorite.vue';

	export default {
		props: ['data'],
		components: { Favorite },
		data() {
			return {
				editing: false,
				body: this.data.body,
				id: this.data.id
			};
		},
		methods: {
			update() {
				axios.patch('/replies/' + this.data.id, {
					body: this.body
				});
				flash('Updated your reply!');
				this.editing = false;
			},
			destroy() {
				axios.delete('/replies/' + this.data.id);

				this.$emit('deleted', this.id);

				flash('Your reply was deleted.');
			}
		}
	}
</script>