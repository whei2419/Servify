<template>
  <div class="nav-container">
    <nav>
      <span class="logo" @click="goto('Home')">
        <img src="images/logo.png" alt="" />
      </span>
      <div ref="mainNav" class="options">
        <button class="menu" @click="isOpen =!isOpen">
          <i class="fa-solid fa-bars"></i>
        </button>
        <ul class="nav-options">
          <li @click="goto('Home')"><span class="icon"><i class="fa-solid fa-list"></i></span> Home</li>
          <li  @click="goto('Requirements')"><span class="icon"><i class="fa-solid fa-file"></i></span> Requirements</li>
          <li><span class="icon"><i class="fa-regular fa-hourglass-half"></i></span> About</li>
        </ul>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
    data(){
        return {
            isOpen:false,
        }
    },
    methods:{
        goto(name){
            this.$router.push({ name: name });
        }
    },
    watch:{
        isOpen: function (newVal,old){
           const nav = this.$refs.mainNav;
            if(newVal){
                nav.classList.add('open');
            }else {
                nav.classList.remove('open');
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.nav-container {
background: #fff;
  top: 0;
  left: 0;
  z-index: 99;
  position: fixed;
  padding: 10px 10%;
  width: 100%;
  height: 60px;
  box-shadow: rgba(111, 178, 230, 0.3) 0px 1px 2px 0px,
    rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;

    .icon {
        display: none;
        color: #0b8be0;
    }

  .menu {
    display: none;
    font-size: 1.5rem;
    color: #0b8be0;
  }

  nav {
    margin: 0 auto;
    max-width: 1200px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    .logo {
      img {
        width: 40px;
        height: 40px;
      }
    }

    ul {
      list-style: none;
      display: flex;
      align-items: center;
      gap: 20px;
    }
  }

  @media screen and (max-width: 900px) {
    .options {
      .menu {
        display: block;
      }
      ul {
        display: none;
      }

      &.open{
        position: relative;

        .icon {
            display: block;
        }

        ul {
            display: block;
            position: absolute;

            top: 40;
            right: 0;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
            flex-direction: row;
            justify-content: start;
            gap: 20px;

            li {
                border-bottom: 1px solid #e6e6e6;
                padding:5px 10px;
                text-align: left;
                display: flex;
                gap: 10px;
            }

        }
      }
    }
  }
}
</style>
