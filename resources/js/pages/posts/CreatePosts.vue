<template>
    <main class="create-post">
        <div class="container">
            <h1>Create Posts!</h1>
            <div class="success-msg" v-if="success">
                <i class="fa fa-check"></i>
                Post Created Successfully
            </div>
            <!-- Contact Form -->
            <div class="contact-form">
                <form @submit.prevent="submit">
                    <!-- Title -->
                    <label for="title"><span>Title</span></label>
                    <input type="text" v-model="field.title" id="title" name="title" />
                    <span class="error" v-if="errors.title">{{ errors.title[0] }}</span>
                    <br />

                    <!-- Image -->
                    <label for="image"><span>Image</span></label>
                    <input type="file" id="image" name="image" @input="grabFile" />
                    <span class="error" v-if="errors.file">{{ errors.file[0] }}</span>
                    <div class="preview">
                        <img :src="url" alt="">
                    </div>
                    <br />

                    <!-- Drop down -->
                    <label for="categories"><span>Choose a category:</span></label>
                    <select name="category_id" id="categories" v-model="field.category_id">
                        <option selected disabled>Select option</option>
                        <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}
                        </option>
                    </select>
                    <span class="error" v-if="errors.category_id">{{ errors.category_id[0] }}</span>
                    <br />

                    <!-- Body-->
                    <label for="body"><span>Body</span></label>
                    <textarea id="body" v-model="field.body" name="body"></textarea>
                    <span class="error" v-if="errors.body">{{ errors.body[0] }}</span>


                    <!-- Button -->
                    <input class="add-post-btn" type="submit" value="Submit" />
                </form>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            success: false,
            field: {
                category_id:''
            },
            errors: {},
            url: '',
            categories: [],
        }
    },
    mounted() {
        axios.get("/api/categories")
            .then((response) => {
                this.categories = response.data
            })
            .catch((error) => {
                console.log(error)


            });
    },
    methods: {
        grabFile(e) {
            const file = e.target.files[0];
            this.field.file = file;
            this.url = URL.createObjectURL(file);
            URL.revokeObjectURL(file)

        },
        submit() {
            axios.post('/api/posts/create', this.field, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            }).then((res) => {
                this.field = {};
                this.field.category_id = '',
                this.url = '',
                this.errors = {};
                this.success = true;
                setInterval(() => {
                    this.success = false
                }, 2500);
            }).catch((error) => {
                this.errors = error.response.data.errors;
            })
        },
    }
};
</script>

<style scoped>
.preview img {
    max-width: 100%;
    max-height: 120px;
}

.create-post {
    background-color: #fff;
    padding: 0 3vw;
}

.container input,
textarea,
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 20px;
    font-size: 16px;
}

h1 {
    text-align: center;
    padding: 60px 0 50px 0;
}

.add-post-btn {
    background-color: black;
    color: white;
    border: none;
    cursor: pointer;
    transition: 0.3s ease;
}

.add-post-btn:hover {
    transform: translateY(-4px);
}
</style>
