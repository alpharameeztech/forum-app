<template>

    <div class="col-sm-12">
        <div v-if="singedIn">

            <div class="form-group">
                <label for="body">Leave a reply</label>
                <vue-tribute :options="tributeOptions">
                    <wysiwyg-component v-model="body" name="body"></wysiwyg-component>
                </vue-tribute>
            </div>

            <button type="submit" class="btn btn-primary" @click="addReply">Submit</button>

        </div>

    </div>

</template>

<script>

    export default {
        props: ['endpoint'],

        data() {
            return {
                body: '',
                tributeOptions: {
                    // symbol that starts the lookup
                    trigger: '@',
                    values: []
                },
                newData: ''
            };
        },
        created() {


        },
        computed: {

            singedIn() {
                return window.App.signedInCheck;
            }
        },

        methods: {

            addReply() {

                var self = this

                this.$root.$emit('loading', true);

                axios.post(this.endpoint, {

                    body: this.body

                })
                    .then(response => {

                        this.body = '';

                        flash('Your reply has been posted', 'success');

                        this.$emit('ReplyPosted', response.data)

                    })
                    .catch(error => {

                        flash(error.response.data, 'danger')

                        self.$root.$emit('loading', false);

                    })
                    .finally(function () {
                        self.$root.$emit('loading', false);
                    });

            },

            getUsers() {

                axios.get('/api/users/list')
                    .then(response => {

                        this.newData = response.data.data
                        //this.tributeOptions.values = response.data;
                        console.log(this.newData)
                        for (var i = 0; i < this.newData.length; i++) {
                            this.tributeOptions.values.push(this.newData[i]);
                        }

                    })
                    .catch(error => {
                    });

            }

        },

        mounted() {

            this.getUsers();

        }
    }
</script>


<style>

    .scroll {
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        position: relative;
    }

    .v-tribute {
        width: 100%;
    }

    .content-editable:empty:before {
        content: attr(placeholder);
        display: block;
        color: #666;
    }

    textarea {
        appearance: none;
        border: none;
        background: #eee;
        padding: 1rem;
        width: 100%;
        border-radius: 0.25rem;
        font-size: 16px;
        height: 100px;
        outline: none;

    &
    :focus {
        background: #fff;
    }

    }


    /
    /
    Tribute-specific styles
    .tribute-container {
        position: absolute;
        top: 0;
        left: 0;
        height: auto;
        max-height: 300px;
        max-width: 500px;
        overflow: auto;
        display: block;
        z-index: 999999;
        border-radius: 4px;
        box-shadow: 0 1px 4px rgba(#000, 0.13);
    }

    .tribute-container ul {
        margin: 0;
        margin-top: 2px;
        padding: 0;
        list-style: none;
        background: #fff;
        border-radius: 4px;
        border: 1px solid rgba(#000, 0.13);
        background-clip: padding-box;
        overflow: hidden;
    }

    .tribute-container li {
        color: #3f5efb;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 14px;
    }

    .tribute-container li.highlight,
    .tribute-container li:hover {
        background: #3f5efb;
        color: #fff;
    }

    .tribute-container li span {
        font-weight: bold;
    }

    .tribute-container li.no-match {
        cursor: default;
    }

    .tribute-container .menu-highlighted {
        font-weight: bold;
    }
</style>
