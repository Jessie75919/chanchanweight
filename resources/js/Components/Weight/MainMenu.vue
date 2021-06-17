<template>
    <div class="recordList">
        <figure><img :src="imgUrl" :alt="title" :title="title"></figure>
        <h2>{{ title }}</h2>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        imgUrl: String,
    },
    setup(props) {
        const baseUrl = 'https://chanchanweight.s3.ap-northeast-2.amazonaws.com/statics/icons/';
        return { title: props.title, imgUrl: baseUrl + props.imgUrl };
    },
};
</script>

