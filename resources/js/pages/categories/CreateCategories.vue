<template>
    <div id="create-categories">
        <div id="contact-us">
            <h1>Create New Category!</h1>
            <div class="success-msg" v-if="success">
                <i class="fa fa-check"></i>
                Category Created Successfully
            </div>
            <div class="contact-form">
                <form @submit.prevent="submit">
                    <label for="name"><span>Name</span></label>
                    <input type="text" id="name" name="name" v-model="fields.name">
                    <input type="submit" value="Submit">
                    <span v-if="errors.name" class="error">{{ errors.name[0] }}</span>
                </form>
            </div>
            <div class="create-categories">
                <router-link :to="{ name: 'CategoriesList' }">Category List <span>&#8594;</span></router-link>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            fields:{},
            errors:{},
            success:false,
        }
    },
    methods:{
        submit(){
            axios.post('/api/categories/create',this.fields).then((res)=>{
                this.fields = {};
                this.errors = {};
                this.success = true;
                setInterval(() => {
                    this.success = false
                }, 2500);
            }).catch((error)=>{
                this.errors = error.response.data.errors;
            })
        }
    }

}
</script>
<style>
#create-categories {
    background-color: #f3f4f6;
    height: 90vh;
    padding: 50px;
}
</style>
