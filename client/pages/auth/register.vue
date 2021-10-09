<template>
  <!-- Section Cards -->
  <section class="authentication">
    <div class="auth-body">
      <h1 class="text-uppercase fw-500 mb-4 text-center font-22">
        Register
      </h1>
      <form class="auth-form" @submit.prevent="submit" @keydown="form.onKeydown($event)">
        <div class="form-group">
          <input
            type="text"
            name="name"
            v-model.trim="form.name"
            class="form-control form-control-lg font-14 fw-300"
            placeholder="Full Name"
          />
        </div>
        <div class="form-group">
          <input
            type="text"
            name="username"
            v-model.trim="form.username"
            class="form-control form-control-lg font-14 fw-300"
            placeholder="Username"
          />
        </div>
        <div class="form-group">
          <input
            type="text"
            name="email"
            v-model.trim="form.email"
            class="form-control form-control-lg font-14 fw-300"
            placeholder="Email"
          />

          <div v-if="form.errors.has('email')" v-html="form.errors.get('email')"/>
        </div>
        <div class="form-group">
          <input
            type="password"
            name="password"
            v-model.trim="form.password"
            class="form-control form-control-lg font-14 fw-300"
            placeholder="Password"
          />
        </div>
        <div class="form-group">
          <input
            type="password"
            name="password_confirmation"
            v-model.trim="form.password_confirmation"
            class="form-control form-control-lg font-14 fw-300"
            placeholder="Confirm Password"
          />
        </div>

        <div class="text-right">
          <button type="submit" class="btn btn-primary primary-bg-color font-16 fw-500 text-uppercase">
            Register
          </button>
        </div>
        <p class="font-14 fw-400 text-center mt-4">
          Already have an account?
          <nuxt-link class="color-blue" :to="{name:'login'}">Login</nuxt-link>
        </p>
      </form>
    </div>
  </section>
  <!-- End Cards -->
</template>

<script>
import Form from 'vform';


export default {

  data() {
    return {
      form: new Form({
        username: '',
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      })
    }
  },

  methods: {
    async submit() {
      this.form.post(`${process.env.API_URL}/register`).then(res => {
        console.log(res);
      }).catch(err => {
        console.log(err);
      })
    }
  }

}
</script>

<style>

</style>
