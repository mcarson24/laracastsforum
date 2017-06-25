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
				    by <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a>
				    <span class="reply-time" v-text="ago"></span>
				</h5>
					<div v-if="signedIn">
				    	<favorite :reply="data"></favorite>
			    	</div>
			    	<div v-else>
				    	<span class="glyphicon glyphicon-heart mr-quarter"></span>
			    		<span>{{ data.favoritesCount }}</span>
		    		</div>
		    </div>
		</div>
			<div class="panel-footer level" v-show="canUpdate">
				<button class="btn btn-default btn-xs mr-1" @click="editing = !editing">Edit</button>
				<button class="btn btn-default btn-xs" @click="destroy">Delete</button>
			</div>
	</div>
</template>

<script>
	import Favorite from './Favorite.vue';
	import moment from 'moment';

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
		computed: {
			signedIn() {
				return window.App.signedIn;
			},
			canUpdate() {
				return this.authorize(user => this.data.user_id === user.id);
			},
			ago() {
				return moment(this.data.created_at).parseZone('UTC').fromNow();
			}
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