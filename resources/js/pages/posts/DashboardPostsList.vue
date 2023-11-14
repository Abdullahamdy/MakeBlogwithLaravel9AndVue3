<template>
    <div class="categories-list">

        <h1>Posts List</h1>
        <!-- success message -->
        <div class="success-msg" v-if="success">
            <i class="fa fa-check"></i>
            Post deleted successfully
        </div>
        <div class="item" v-for="(post, index) in postsList" :key="index">
            <span>{{ ++index }}</span>
            <p>{{ post.title }}</p>
            <div>
                <router-link class="edit-link" :to="{ name: 'EditPosts', params: { slug: post.slug } }">Edit</router-link>
            </div>

            <input @click="deletePost(post)" type="submit" value="Delete" class="delete-btn" />
        </div>
        <div class="index-categories">
            <router-link :to="{ name: 'CreatePosts' }">Create post<span>&#8594;</span></router-link>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["updateSidebar"],
    data() {
        return {
            postsList: [],
            success: false,
        };
    },
    methods: {
        deletePost(postToDelete) {
            axios.delete("/api/posts/" + postToDelete.id)
                .then((response) => {
                    console.log(response);
                    this.postsList = this.postsList.filter(post => post.id !== postToDelete.id);
                    this.success = true;
                    setInterval(() => {
                        this.success = false
                    }, 2500);
                    this.fetchCategories();
                })
                .catch((error) => {

                });
        },
    },

    mounted() {

        axios
            .get("/api/dashboard-posts")
            .then((response) => {

                this.postsList = response.data.data;
            })
            .catch((error) => {
                if (error.response.status === 401) {
                    this.$emit("updateSidebar");
                    localStorage.removeItem("authenticated");
                    this.$router.push({ name: "Login" });
                }
            });
    },


};
</script>
<style scoped >
.categories-list {
    min-height: 100vh;
    background: #fff;
}

.categories-list h1 {
    font-weight: 300;
    padding: 50px 0 30px 0;
    text-align: center;
}

.categories-list .item {
    display: flex;
    justify-content: right;
    align-items: center;
    max-width: 300px;
    margin: 0 auto !important;
}

.categories-list .item p {
    font-size: 16px;
}

.categories-list .item p,
.categories-list .item div,
.categories-list .item {
    margin: 15px 8px;
}

.categories ul li {
    list-style: none;
    background-color: #494949;
    margin: 20px 5px;

    border-radius: 15px;
}

.categories ul {
    display: flex;
    justify-content: center;
}

.categories a {
    color: white;
    padding: 10px 20px;
    display: inline-block;
}

.create-categories a,
.index-categories a {
    all: revert;
    margin: 20px 0;
    display: inline-block;
    text-decoration: none;
}

.create-categories a span,
.index-categories a span {
    font-size: 22px;
}

.index-categories {
    text-align: center;
}
</style>
