<template>
  <v-list-group no-action sub-group>
                            <template v-slot:activator>
                                <v-list-item-icon>
                                    <v-icon>mdi-quora</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title
                                        >Quizzes</v-list-item-title
                                    >
                                </v-list-item-content>
                            </template>

                            <v-list-item
                                v-for="(name, id) in quiz"
                                :key="id"
                                link
                            >
                            <!-- <inertia-link :href="route('question.edit', id)"> -->
                            <!-- <v-list-item
                                link
                                v-for="(name, id) in quiz"
                                :key="id"
                            >  -->

                            <v-list-item-title>
                                {{name}}
                            </v-list-item-title>
                            </v-list-item>
                            <!-- </inertia-link> -->

                            <!-- <inertia-link :href="route('question.edit', question.id)"> -->
                                <!-- <v-list-item-icon>
                                    <v-icon v-text="icon"></v-icon>
                                </v-list-item-icon> -->

                                <!-- <v-list-item-title
                                    v-text="title"
                                ></v-list-item-title> -->
                                <!-- <inertia-link :href="route('question.edit', id)">
                                  <v-btn text color="teal white--text"> {{name}} </v-btn>
                                </inertia-link> -->
                            <!-- </inertia-link> -->
                            <!-- </v-list-item> -->
                        </v-list-group>
</template>

<script>
export default {
}
</script>

<style>

</style>