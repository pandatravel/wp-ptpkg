<?php

/**
 * Template part for displaying packages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ptpkg
 * @since 1.0
 * @version 1.2
 * @author Ammon Casey
 */

use Ptpkg\lib\common\Api;

// Get an instance of the ptpkg api
$api = new Api();

/**
 * Custom meta fields
 */
$currentID = get_the_ID();
$package = $api->get_client()->tours()->show_wp($currentID);
$packageBannerUrl = wp_get_attachment_image_url(get_post_meta($currentID, 'package-banner', true), 'full');
$packageTeaser = get_post_meta($currentID, 'package-teaser', true);
$packagePrice = get_post_meta($currentID, 'package-price', true);
$packageLocation = get_post_meta($currentID, 'package-location', true);
$packageSEOAd = get_post_meta($currentID, 'package-seo-ad', true);
$packageSEOContent = get_post_meta($currentID, 'package-seo-content', true);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('package-book'); ?>>

    <v-layout row>
        <v-flex xs10 offset-xs1>

            <v-card>
                <v-card-text>
                    <h2 class="card-title text-primary text-sm-center"><?php the_title(); ?></h2>
                    <!-- package total -->
                </v-card-text>
                <v-divider class="mt-0"></v-divider>
                <v-card-text>

                </v-card-text>
                <booking-form endpoint="package" inline-template>
                    <v-form action="/wp-json/ptpkg/v1/package" @submit.prevent="onSubmit" method="post">
                        <v-stepper v-model="step" flat>
                            <v-stepper-header>
                                <v-stepper-step step="1" :complete="step > 1">Select Tour Package</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step step="2" :complete="step > 2">Review Tour</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step step="3" :complete="step > 3">Purchase Tour</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step step="4">Confirmation</v-stepper-step>
                            </v-stepper-header>
                            <v-stepper-items>

                                <v-stepper-content step="1">
                                    <v-layout row>
                                        <v-flex xs12>
                                            <v-btn @click="addRoom" small outline color="info"><v-icon>add</v-icon> Add Room</v-btn>
                                            <span class="grey--text lighten-1"><strong>Note:</strong> The maximum occupants per room is <span class="primary--text">{{ room_max }}</span></span>
                                        </v-flex>
                                    </v-layout>
                                    <div v-for="(room, roomIndex) in form.rooms">
                                    <v-layout row pb-1>
                                          <v-flex xs12>
                                            <v-card class="card--flex-toolbar mx-1 my-1">
                                                <v-toolbar card light dense>
                                                    <v-toolbar-title class="body-2 grey--text">Room {{ roomIndex + 1 }}</v-toolbar-title>
                                                    <v-toolbar-items>
                                                        <v-btn @click="removeRoom(roomIndex)" flat dark color="red"><v-icon dark>remove</v-icon> remove</v-btn>
                                                    </v-toolbar-items>
                                                    <v-spacer></v-spacer>
                                                    <v-toolbar-items>
                                                        <v-btn @click="addAdult(roomIndex)" :disabled="hasVacancy(roomIndex) == false" color="info" flat><v-icon dark>add</v-icon> Adult &nbsp;<small>(19+)</small></v-btn>
                                                        <v-btn @click="addChild(roomIndex)" :disabled="hasVacancy(roomIndex) == false" color="info" flat><v-icon dark>add</v-icon> Child &nbsp;<small>(2-18)</small></v-btn>
                                                    </v-toolbar-items>
                                                </v-toolbar>
                                                <v-divider class="mt-0"></v-divider>
                                                <v-container grid-list-xl fluid class="px-3 py-1">
                                                    <v-layout v-for="(traveler, travelerIndex) in room.travelers" wrap>
                                                        <v-flex xs3>
                                                            <v-text-field persistent-hint :hint="traveler.adult ? 'Adult (19+)' : 'Child (2-18)'" id="form-first_name" label="First Name" name="first_name" v-model="traveler.first_name" required></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field id="form-middle_name" label="Middle Name" name="middle_name" v-model="traveler.middle_name"></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs3>
                                                            <v-text-field id="form-last_name" label="Last Name" name="last_name" v-model="traveler.last_name" required></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-select label="Gender" :items="['Male', 'Female']" required></v-select>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-btn @click="removeTraveler(roomIndex, travelerIndex)" color="red" small flat dark fab><v-icon>delete_forever</v-icon></v-btn>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                    </div>

                                    <v-btn color="primary" @click.native="step = 2">Continue</v-btn>

                                </v-stepper-content>

                                <v-stepper-content step="2">

                                    <v-text-field label="Street" v-model="form.street" required></v-text-field>
                                    <v-text-field label="City" v-model="form.city" required></v-text-field>
                                    <v-text-field label="State" v-model="form.state" required></v-text-field>

                                    <v-btn flat @click.native="step = 1">Previous</v-btn>
                                    <v-btn color="primary" @click.native="step = 3">Continue</v-btn>

                                </v-stepper-content>

                                <v-stepper-content step="3">

                                    <v-card color="grey lighten-1" class="mb-5" height="200px">
                                        <slot name="step-3"></slot>
                                    </v-card>

                                    <v-btn flat @click.native="step = 2">Previous</v-btn>
                                    <v-btn color="primary" @click.native="step = 4">Continue</v-btn>
                                </v-stepper-content>
                                <v-stepper-content step="4">
                                    <v-card color="grey lighten-1" class="mb-5" height="200px">
                                        <slot name="step-4"></slot>
                                    </v-card>

                                    <v-btn flat @click.native="step = 3">Previous</v-btn>
                                    <v-btn color="primary" type="submit">Submit</v-btn>
                                </v-stepper-content>
                            </v-stepper-items>
                        </v-stepper>
                    </v-form>
                </booking-form>

            </v-card>

        </v-flex>
    </v-layout>

</article><!-- #post-## -->
