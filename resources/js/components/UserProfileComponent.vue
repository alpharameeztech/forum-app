<script>

    export default {

        props: [],

        data(){

            return{
                file: null,
                imageUrl: null,
                logo: '',
                isEditing: false
            }
        },

        mounted(){

        },
        methods: {

            uploadingImage()
            {

                this.$root.$emit('loading', true);

            },

            save(){


            }

        },

    }
</script>
