<template>
    <v-app>
        <AppLayout />
        <v-main>
            <v-container>
                <div class="mainSize" align="center">
                    <v-alert
                        border="top"
                        colored-border
                        type="info"
                        elevation="2"
                    >
                        Add a Question
                    </v-alert>
                    <v-form
                        ref="form"
                        class="mt-6"
                        v-model="valid"
                        lazy-validation
                    >
                        <v-select
                            :items="quizzes"
                            v-model="form.quiz"
                            name="quiz"
                            :rules="quizRules"
                            item-value="id"
                            item-text="name"
                            label="Select a quiz"
                        />
                        <v-text-field
                            v-model="form.question"
                            :rules="questionRules"
                            label="Enter Question"
                            required
                            filled
                        ></v-text-field>
                        <input
                            type="file"
                            ref="image"
                            @change="updateImagePreview"
                        />
                        <div v-show="preview">
                            <span
                                class="block w-20 h-20 rounded-full"
                                :style="
                                    'width: 5rem; height: 5rem; border-radius: 999px; display: block; background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' +
                                        preview +
                                        '\');'
                                "
                            >
                            </span>
                        </div>
                        <div v-for="n in 4" :key="n">
                            <v-text-field
                                v-model="form.options[n]"
                                solo
                                label="Enter option"
                                clearable
                                :rules="optionRules"
                            >
                                <template v-slot:append-outer>
                                    <v-switch
                                        v-model="form.correct_answer"
                                        inset
                                        :value="n"
                                    ></v-switch>
                                </template>
                            </v-text-field>
                        </div>
                        <v-btn
                            :disabled="!valid"
                            color="primary"
                            class="mr-4 px-16 py-6"
                            @click="store(form)"
                        >
                            Create
                        </v-btn>
                    </v-form>
                </div>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
import AppLayout from "../../Layouts/AppLayout";
export default {
    data() {
        return {
            form: this.$inertia.form(
                {
                    _method: "POST",
                    image: null,
                    question: "",
                    quiz: "",
                    options: [],
                    correct_answer: 1
                },
                {
                    resetOnSuccess: false
                }
            ),
            preview: null,
            valid: true,
            /* form: {
                question: "",
                quiz: "",
                options: [],
                image: null,
                correct_answer: 1
            }, */
            quizRules: [v => !!v || "Quiz is required"],
            questionRules: [v => !!v || "Question is required"],
            optionRules: [v => !!v || "Option is required"]
        };
    },
    components: {
        AppLayout
    },
    props: ["quizzes"],
    methods: {
        updateImagePreview() {
            const reader = new FileReader();

            reader.onload = e => {
                this.preview = e.target.result;
            };

            reader.readAsDataURL(this.$refs.image.files[0]);
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        store: function(data) {
            if (this.$refs.form.validate()) {
                if (this.$refs.image) {
                    this.form.image = this.$refs.image.files[0];
                }
                /* this.form.post(route("/photos.store"), {
                    preserveScroll: true
                }); */
                this.form.post("/question", data, {
                    forceFormData: true
                });
                //this.$inertia.post("/question", data);
                this.snackbar = true;
            }
        }
    }
};
</script>
<style scoped>
.mainSize {
    max-width: 650px;
    margin: auto;
    margin-top: 20px;
}
</style>
