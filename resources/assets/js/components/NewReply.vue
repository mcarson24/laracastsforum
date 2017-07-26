<template>
	<div class="row">
	    <div class="col-md-10 col-md-offset-1 mr-top-25">
	    	<div class="reply" v-if="signedIn">
                <div class="form-group">
                    <textarea name="body" 
                    		  id="body"
                    		  class="form-control" 
                    		  placeholder="Got something to say?" 
                    		  v-model="body"
                    		  required>
                	</textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default btn-block" @click="saveReply">Post</button>
                </div>
            </div>
            <div class="guestLinks" v-else>
	            <p class="text-center"><a href="/register">Register</a> to participate in this discussion.</p>
	            <p class="text-center">Already a member? <a href="/login">Sign in here</a>!</p>
            </div>
	    </div>
	</div>
</template>

<script>
	import 'jquery.caret';
	import 'at.js';
	export default {
		data() {
			return {
				body: ''
			}
		},
		computed: {
			signedIn() {
				return window.App.signedIn;
			},
			csrfToken() {
				return window.App.csrfToken;
			},
			endpoint() {
				return location.pathname + '/replies';
			}
		},
		mounted() {
			$('#body').atwho({
			  	at: "@",
			  	delay: 750,
			  	callbacks: {
			    	remoteFilter: function(query, callback) {
				      	$.getJSON("/api/users", {name: query}, function(usernames) {
				      	  callback(usernames)
				      	});
			    	}
			  	}
			});
		},
		methods: {
			saveReply() {
				axios.post(this.endpoint, {
					'body': this.body
				}).then(({data}) => {
					this.body = '';
					flash('Your reply has been added!');
					this.$emit('created', data);
				}).catch(errors => {
					console.log(errors.response);
					flash(errors.response.data, 'danger');
				});
			}
		}
	}
</script>