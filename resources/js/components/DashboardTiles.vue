<template>
  <div class="tiles">
    <div class="tile-icon">
        <i :class="data.icon"></i>
    </div>
    <div class="content">
        <h1>{{ data.count }}</h1>
        <p>{{ data.title }}</p>
    </div>
  </div>
</template>

<script>
export default {
    props: {
        data:{
            type:Object,
            // required:true,
        }
    }

}
</script>

<style lang="scss" scoped>
.tiles {
    width: 100%;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background: #fff;
    position: relative;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 150px;
    text-align: center;

    .content {
        h1 {
        font-size: 3.5rem;
    }

    p {
        color: #FF9F2F ;
        font-weight: 500;
        font-size: 16px;
    }
    }



    .tile-icon {
        position: absolute;
        top: 0;
        left: 0;
        width: 70px;
        height: 70px;
        background: #f6ba7656;
        border-radius: 8px 0 70px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        i{
            margin-left: -10px;
            margin-top: -10px;
            color: #FF9F2F ;
            font-size: 25px;
        }
    }


}

</style>
