<template>
  <div>
      
      <SiteNavigation></SiteNavigation>
      <div class="wrapper">
            <!-- Start Hero -->
            <!-- <section class="hero text-center bg-light shadow-sm text-blue">
                <div class="container">
                    <h1 class="font-28 fw-600 text-uppercase">
                        What are you working on?
                    </h1>
                    <p class="font-16 fw-400">
                        DesignHouse is where designers get inspired.
                    </p>
                    <a class="mt-4 btn btn-danger font-16 fw-400" href="#"
                        >Sign up for Free</a
                    >
                </div>
            </section> -->
            <!-- End Hero -->
           <nuxt/>
      </div>

     <SiteFooter></SiteFooter>

  </div>
</template>

<script>

import SiteNavigation from './partials/navigation.vue';
import SiteFooter from './partials/footer';

export default {
    components:{
    SiteNavigation,
    SiteFooter
    }
}
</script>

<style>

</style>