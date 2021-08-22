import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

import home from "../pages/home.vue";
import about from "../pages/about.vue";

import profile from "../pages/profile.vue";
import users from "../pages/users.vue";

// import login from '../pages/login.vue'
// import registration from '../pages/registration.vue'

import dashboard from "../pages/dashboard.vue";
import contact from "../pages/contact.vue";
import privacy from "../pages/privacy.vue";
import categories from "../pages/category.vue";
import news from "../pages/news.vue";
import questions from "../pages/questions.vue";
import notifications from "../pages/notifications.vue";

let Admin = ["Admin"];

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "dashboard",
            component: dashboard,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "dashboard"
            }
        },

        {
            path: "/categories",
            name: "categories",
            component: categories,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "Categories"
            }
        },
        {
            path: "/news",
            name: "news",
            component: news,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "News"
            }
        },
        {
            path: "/questions",
            name: "questions",
            component: questions,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "Questions"
            }
        },
        {
            path: "/about",
            name: "about",
            component: about,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "About"
            }
        },
        {
            path: "/privacy",
            name: "privacy",
            component: privacy,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "Privacy"
            }
        },
        {
            path: "/notifications",
            name: "notifications",
            component: notifications,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "Notifications"
            }
        },

        {
            path: "/contact",
            name: "contact",
            component: contact,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "Contact"
            }
        },

        {
            path: "/profile",
            name: "profile",
            component: profile,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "Profile"
            }
        },

        {
            path: "/users",
            name: "users",
            component: users,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "Users"
            }
        },
        // {
        //     path: '/login',
        //     name: 'login',
        //     component: login,
        //     meta: {
        //        // guest: true,
        //        // allowedUserType: ['All'],
        //         title: 'Sign In',
        //     }

        //   },
        // {
        //     path: '/registration',
        //     name: 'registration',
        //     component: registration,
        //     meta: {
        //        // guest: true,
        //        // allowedUserType: ['All'],
        //         title: 'Sign Up',
        //     }

        //   },

        {
            path: "/dashboard",
            name: "dashboard",
            component: dashboard,
            meta: {
                // guest: true,
                // allowedUserType: ['All'],
                title: "Dashboard"
            }
        }
    ]
});
