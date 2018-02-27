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
// $package = $api->get_client()->tours()->show_wp($currentID);
$packageBannerUrl = wp_get_attachment_image_url(get_post_meta($currentID, 'package-banner', true), 'full');
$packageTeaser = get_post_meta($currentID, 'package-teaser', true);
$packagePrice = get_post_meta($currentID, 'package-price', true);
$packageLocation = get_post_meta($currentID, 'package-location', true);
$packageSEOAd = get_post_meta($currentID, 'package-seo-ad', true);
$packageSEOContent = get_post_meta($currentID, 'package-seo-content', true);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('package-book'); ?>>

    <v-layout row>
        <v-flex xs12>

            <v-card>
                <v-card-text>
                    <h2 class="card-title text-primary text-sm-center"><?php the_title(); ?></h2>
                    <!-- package total -->
                </v-card-text>
                <v-divider class="my-0"></v-divider>
                <booking-form endpoint="package" inline-template>
                    <v-form @submit.prevent="onSubmit" method="post">
                        <v-layout row v-cloak>
                            <v-flex xs6>
                                <v-card-text class="mt-4 pt-5">
                                    <pre>{{ errors.items.map(e => e.msg) }}</pre>
                                </v-card-text>
                            </v-flex>
                            <v-flex xs6>
                                <dl class="dl-horizontal px-3">
                                    <dt class="blue-grey--text darken-4 text-xs-left">Itinerary Price</dt>
                                    <dd class="blue-grey--text darken-4 text-xs-right">{{ subTotal | currency }}</dd>
                                    <dt v-if="form.insurance" class="blue-grey--text darken-4 text-xs-left">Travel Insurance</dt>
                                    <dd v-if="form.insurance" class="blue-grey--text darken-4 text-xs-right">{{ insurance | currency }}</dd>
                                    <v-divider class="mt-1 mb-3"></v-divider>
                                    <dt class="title primary--text text-xs-left">Total Price</dt>
                                    <dd class="title primary--text text-xs-right">{{ total | currency }}</dd>
                                    <p class="caption blue-grey--text lighten-5 mb-0">* Taxes are included</p>
                                    <p class="caption blue-grey--text lighten-3 mb-0">* Additional fees may apply. See our <a href="#" title="terms and conditions">terms and conditions</a> for details.</p>
                                </dl>
                            </v-flex>
                        </v-layout>
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
                                            <v-btn @click="addRoom" small flat color="info"><v-icon>add</v-icon> Add Room</v-btn>
                                            <span class="grey--text lighten-1"><strong>Note:</strong> The maximum occupants per room is <span class="primary--text">{{ room_max }}</span></span>
                                        </v-flex>
                                    </v-layout>

                                    <!-- <room v-for="(room, roomIndex) in form.rooms" :room="form.rooms[roomIndex]" :index="roomIndex" :room_max="room_max" :total_travelers="totalTravelers" :max_travelers="maxTravelers" @remove-room="removeRoom" @remove-traveler="removeTraveler"></room> -->
                                    <v-layout v-for="(room, roomIndex) in form.rooms" row pb-1>
                                        <v-flex xs12>
                                            <v-card class="card--flex-toolbar mx-1 my-1">
                                                <v-toolbar card light dense>
                                                    <v-toolbar-title class="body-2 grey--text">Room {{ roomIndex + 1 }}</v-toolbar-title>
                                                    <v-toolbar-items>
                                                        <v-btn @click="removeRoom(roomIndex)" flat dark color="red"><v-icon dark>close</v-icon> remove</v-btn>
                                                    </v-toolbar-items>
                                                    <v-spacer></v-spacer>
                                                    <v-toolbar-items>
                                                        <v-btn @click="addAdult(roomIndex)" :disabled="hasVacancy(roomIndex) == false || totalTravelers >= maxTravelers" color="info" flat><v-icon dark>add</v-icon> Adult &nbsp;<small>(19+)</small></v-btn>
                                                        <v-btn @click="addChild(roomIndex)" :disabled="hasVacancy(roomIndex) == false || totalTravelers >= maxTravelers" color="info" flat><v-icon dark>add</v-icon> Child &nbsp;<small>(2-18)</small></v-btn>
                                                    </v-toolbar-items>
                                                </v-toolbar>
                                                <v-divider class="mt-0"></v-divider>
                                                <v-container grid-list-xl fluid class="px-3 py-1">
                                                    <!-- <traveler v-for="(traveler, travelerIndex) in room.travelers" :key="travelerIndex" v-model="room.travelers[travelerIndex]" :index="{traveler:travelerIndex, room:roomIndex}" @remove-traveler="removeTraveler"></traveler> -->
                                                    <v-layout v-for="(traveler, travelerIndex) in room.travelers" wrap>
                                                        <v-flex xs3>
                                                            <v-text-field
                                                                v-model="traveler.first_name"
                                                                label="First Name"
                                                                :id="'first_name-' + roomIndex  + '-' + travelerIndex"
                                                                persistent-hint
                                                                :hint="traveler.adult ? 'Adult (19+)' : 'Child (2-18)'"
                                                                :error-messages="errors.collect('first_name-' + roomIndex  + '-' + travelerIndex)"
                                                                v-validate="'required'"
                                                                :data-vv-name="'first_name-' + roomIndex  + '-' + travelerIndex"
                                                                data-vv-as="First Name"
                                                                required></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field
                                                                v-model="traveler.middle_name"
                                                                label="Middle Name"
                                                                :id="'middle_name-' + roomIndex  + '-' + travelerIndex"></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-text-field
                                                                v-model="traveler.last_name"
                                                                label="Last Name"
                                                                :id="'last_name-' + roomIndex  + '-' + travelerIndex"
                                                                :error-messages="errors.collect('last_name-' + roomIndex  + '-' + travelerIndex)"
                                                                v-validate="'required'"
                                                                :data-vv-name="'last_name-' + roomIndex  + '-' + travelerIndex"
                                                                data-vv-as="Last Name"
                                                                required></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <!-- <birthdate
                                                                v-model="room.travelers[travelerIndex]"
                                                                :index="{traveler:travelerIndex, room:roomIndex}"
                                                                :name="'birthdate-' + roomIndex + '-' + travelerIndex"
                                                                v-validate="'required'"
                                                                :error-messages="errors.collect('birthdate-' + roomIndex  + '-' + travelerIndex)"
                                                                :data-vv-name="'birthdate-' + roomIndex  + '-' + travelerIndex"
                                                                data-vv-as="Birthdate"></birthdate> -->
                                                            <v-text-field
                                                                v-model="traveler.birthdate"
                                                                label="Birthdate"
                                                                :id="'birthdate-' + roomIndex  + '-' + travelerIndex"
                                                                hint="mm/dd/yyy"
                                                                :error-messages="errors.collect('birthdate-' + roomIndex  + '-' + travelerIndex)"
                                                                v-validate="'required|date_format:MM/DD/YYYY'"
                                                                :data-vv-name="'birthdate-' + roomIndex  + '-' + travelerIndex"
                                                                data-vv-as="Birthdate"
                                                                required></v-text-field>
                                                        </v-flex>
                                                        <v-flex xs2>
                                                            <v-select
                                                                v-model="traveler.gender"
                                                                label="Gender"
                                                                :id="'gender-' + roomIndex  + '-' + travelerIndex"
                                                                :items="['Male', 'Female']"
                                                                :error-messages="errors.collect('gender-' + roomIndex  + '-' + travelerIndex)"
                                                                v-validate="'required'"
                                                                :data-vv-name="'gender-' + roomIndex  + '-' + travelerIndex"
                                                                data-vv-as="Gender"
                                                                required></v-select>
                                                        </v-flex>
                                                        <v-flex xs1>
                                                            <v-btn @click="removeTraveler" color="red" small flat dark fab><v-icon>delete_forever</v-icon></v-btn>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>

                                    <v-layout row>
                                        <v-flex xs12>
                                            <v-card class="card--flex-toolbar mx-1 my-1">
                                                <v-toolbar card light dense>
                                                    <v-toolbar-title class="body-2 grey--text">Travel Insurance - <span class="primary--text">{{ package.insurance.name }}</span></v-toolbar-title>
                                                    <v-spacer></v-spacer>
                                                </v-toolbar>
                                                <v-divider class="mt-0"></v-divider>
                                                <v-card-text>
                                                    <div v-html="package.insurance.description"></div>
                                                    <p>This plan is available for your itinerary at a cost of {{ premiumPrice | currency }} per traveler.</p>
                                                    <v-checkbox
                                                          v-model="form.insurance"
                                                          value="1"
                                                          label="Purchase Insurance"
                                                          type="checkbox"></v-checkbox>
                                                </v-card-text>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>

                                    <v-layout row>
                                        <v-flex xs12>
                                            <v-checkbox
                                                  v-model="form.agree_terms"
                                                  value="1"
                                                  label="Agree to the Terms and Conditions"
                                                  :error-messages="errors.collect('agree_terms')"
                                                  v-validate="'required'"
                                                  data-vv-name="agree_terms"
                                                  data-vv-as="Agree to Terms & Conditions"
                                                  type="checkbox"
                                                  required></v-checkbox>
                                        </v-flex>
                                    </v-layout>

                                    <v-btn color="primary" @click.native="step = 2">Continue</v-btn>
                                    <v-btn color="primary" type="submit">Submit</v-btn>

                                </v-stepper-content>

                                <v-stepper-content step="2">

                                    <!-- <v-text-field label="Street" v-model="form.street" required></v-text-field>
                                    <v-text-field label="City" v-model="form.city" required></v-text-field>
                                    <v-text-field label="State" v-model="form.state" required></v-text-field> -->

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
