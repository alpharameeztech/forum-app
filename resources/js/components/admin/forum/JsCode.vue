<template>
    <v-card>


        <v-card
            v-cloak
            :class="`d-flex justify-center mb-6`"
            flat>
            <v-card-text>

                <v-textarea
                    v-model="jsCode"
                    background-color="amber lighten-4"
                    color="orange orange-darken-4"
                    label="Paste your custom javascript under <script></script> these brackets"
                ></v-textarea>

                <v-btn color="blue darken-1" class="update" outlined @click="save">Save</v-btn>

            </v-card-text>

        </v-card>

    </v-card>
</template>
<script>

    export default {
        data() {
            return {
                jsCode: ''
            }

        },

        computed: {},

        watch: {},

        created() {
            this.initialize()
        },
        mounted() {
            var self = this;
        },

        methods: {
            initialize() {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/theme/settings')
                    .then(function (response) {

                        self.jsCode = response.data.js_code
                        self.$root.$emit('loading', false)

                    })
                    .catch(function (error) {

                    });

            },


            close() {

            },

            save() {
                // alert(this.selectedThemeId)

                var self = this;

                this.$root.$emit('loading', true);

                axios.patch('/api/theme/settings', {
                    js_code: this.jsCode,
                })
                    .then(function (response) {

                        self.$root.$emit('loading', false)

                        flash('Changes Saved')

                    })
                    .catch(function (error) {

                    });


                this.close()
            },
        },

    }
</script>

<style>
    .update {
        margin-top: 22px;
    }
</style>
