<template>
	<div class="panel" :class="isBest ? 'panel-success' : ''">
		<div :id="'reply-' + this.id" class="panel-heading">
			<div v-if="editing">
				<form @submit="update">
					<div class="form-group">
						<textarea class="form-control" v-model="body" required></textarea>
					</div>
					<button class="btn btn-default btn-xs" type="button" @click="editing = false">Cancel</button>
					<button class="btn btn-default btn-xs">Update</button>
				</form>
			</div>
			<div v-else v-html="body"></div>
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
			<div class="panel-footer level">
				<div v-show="canUpdate">
					<button class="btn btn-default btn-xs mr-1" @click="editing = !editing">Edit</button>
					<button class="btn btn-default btn-xs" @click="destroy">Delete</button>
				</div>
				<button class="btn btn-default btn-xs m-l-auto" @click="markBestReply" v-show="!isBest">Best Reply</button>
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
				id: this.data.id,
				isBest: false
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
				}).catch(errors => {
					flash(errors.response.data, 'danger');
				});
				flash('Updated your reply!');
				this.editing = false;
			},
			destroy() {
				axios.delete('/replies/' + this.data.id);

				this.$emit('deleted', this.id);

				flash('Your reply was deleted.');
			},
			markBestReply() {
				this.isBest = true;
			}
		}
	}
</script>
