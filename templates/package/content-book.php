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
                    <div v-if="success == false" :class="{'loading': loading}">
                        <v-form @submit.prevent="onSubmit" method="post"  id="booking-form">
                            <v-layout row v-cloak>
                                <v-flex xs6>
                                    <v-card-text class="mt-4 pt-5">

                                    </v-card-text>
                                </v-flex>
                                <v-flex xs6>
                                    <dl class="dl-horizontal px-3">
                                        <dt class="blue-grey--text darken-4 text-xs-left">Itinerary Price</dt>
                                        <dd class="blue-grey--text darken-4 text-xs-right">{{ subTotal | currency }}</dd>
                                        <dt v-if="form.insurance" class="blue-grey--text darken-4 text-xs-left">Travel Insurance</dt>
                                        <dd v-if="form.insurance" class="blue-grey--text darken-4 text-xs-right">{{ insurance | currency }}</dd>
                                        <dt v-if="form.discount" class="blue-grey--text darken-4 text-xs-left">Discount ({{ package.discount.name }})</dt>
                                        <dd v-if="form.discount" class="blue-grey--text darken-4 text-xs-right">-{{ discount | currency }}</dd>
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
                                    <v-stepper-step step="1" :complete="step > 1">Build your Tour Package</v-stepper-step>
                                    <v-divider></v-divider>
                                    <v-stepper-step step="2" :complete="step > 2">Traveler Information</v-stepper-step>
                                    <v-divider></v-divider>
                                    <v-stepper-step step="3" :complete="step > 3">Payment</v-stepper-step>
                                    <v-divider></v-divider>
                                    <v-stepper-step step="4">Confirmation</v-stepper-step>
                                </v-stepper-header>
                                <v-stepper-items>

                                    <v-stepper-content step="1">

                                        <v-container v-if="step === 1" grid-list-md fluid>

                                            <h2 class="headline primary--text text-uppercase">Build your Tour Package</h2>
                                            <p class="mx-3">Add all the travelers in your party</p>
                                            <p class="grey--text mx-3 my-0  text-sm-right">Fields marked with an <font-awesome-icon icon="asterisk" size="xs"></font-awesome-icon> are required</p>

                                            <v-layout row>
                                                <v-flex xs12>
                                                    <v-btn @click="addRoom" :disabled="roomsAvailable === 0" small flat color="info"><v-icon>add</v-icon> Add Room</v-btn>
                                                    <span class="grey--text lighten-1"><strong>Note:</strong> The maximum occupants per room is <span class="primary--text">{{ room_max }}</span></span>
                                                </v-flex>
                                            </v-layout>

                                            <!-- room component -->
                                            <room v-for="(room, roomIndex) in form.rooms" :room="form.rooms[roomIndex]" :index="roomIndex" :$v="$v" :rates="package.rates" :premiums="package.insurance.premiums" :room_max="room_max" @update-room="updateRoom" @remove-room="removeRoom" @remove-traveler="removeTraveler"></room>

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
                                                                  :value="true"
                                                                  :true-value="true"
                                                                  :false-value="false"
                                                                  label="Purchase Insurance"
                                                                  type="checkbox"></v-checkbox>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-flex>
                                            </v-layout>

                                        </v-container>

                                        <v-btn color="primary" @click.native="nextStep(2)">Continue</v-btn>

                                    </v-stepper-content>

                                    <v-stepper-content step="2">

                                        <v-container v-if="step === 2" grid-list-md fluid class="px-3 py-1">

                                            <h2 class="headline primary--text text-uppercase">Traveler Information</h2>
                                            <blockquote class="caption grey--text text--darken-1 pr-5"><strong>Note:</strong> The Transportation Security Administration of the U.S. Department of Homeland Security (TSA) requires the information marked in "*". View the TSA privacy notice for details. Please enter the traveler's name(s) exactly as shown on your government-issued photo identification used for travel.</blockquote>

                                            <v-divider></v-divider>

                                            <div v-for="(room, roomIndex) in form.rooms">
                                                <traveler-extended v-for="(traveler, travelerIndex) in room.travelers" :key="'traveler-' + travelerIndex" v-model="room.travelers[travelerIndex]" :index="{traveler:travelerIndex, room:roomIndex}" :$v="$v" :step="step" @remove-traveler="removeTraveler"></traveler>
                                            </div>
                                            <p class="caption blue-grey--text"><strong>note:</strong> Seat selections are requested from the airline, but cannot be guaranteed. </p>

                                            <v-divider></v-divider>

                                            <p class="caption blue-grey--text mb-1">If you have any special requests, please let us know (i.e. special needs). Your requests will be forwarded to our travel partners but are not guaranteed. Please inquire directly with the airline/car rental company/hotel at time of check-in to see if they can fulfill your requests.</p>
                                            <v-text-field
                                                v-model="form.requests"
                                                name="requests"
                                                label="Special Requests"
                                                hint="Maximum of 255 characters"
                                                :rules="[(v) => v.length <= 255 || 'Max 255 characters']"
                                                :counter="255"
                                                multi-line></v-text-field>

                                        </v-container>

                                        <v-btn flat @click.native="step = 1">Previous</v-btn>
                                        <v-btn color="primary" @click.native="nextStep(3)">Continue</v-btn>

                                    </v-stepper-content>

                                    <v-stepper-content step="3">

                                        <v-container v-if="step === 3" grid-list-md fluid>

                                            <v-layout row wrap>
                                                <v-flex sm12>
                                                    <h2 class="headline primary--text text-uppercase">Payment Information</h2>
                                                    <v-divider></v-divider>
                                                </v-flex>
                                            </v-layout>
                                            <v-layout row wrap>
                                                <v-flex sm7>


                                                    <h4 class="small-title primary--text text-thin">Billing Information</h4>

                                                    <v-layout row wrap>
                                                        <v-flex sm6>
                                                            <v-text-field
                                                                v-model="form.address"
                                                                label="Address"
                                                                :name="'address'"
                                                                :id="'address'"
                                                                ref="address"
                                                                :error-messages="billingAddressErrors()"
                                                                @input="$v.form.address.$touch()"
                                                                @blur="$v.form.address.$touch()"
                                                                required></v-text-field>
                                                        </v-flex>
                                                        <v-flex sm6>
                                                            <v-text-field
                                                                v-model="form.address2"
                                                                label="Address 2"
                                                                :name="'address2'"
                                                                :id="'address2'"
                                                                ref="address2"></v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout row wrap>
                                                        <v-flex sm4>
                                                            <v-text-field
                                                                v-model="form.city"
                                                                label="City"
                                                                :name="'city'"
                                                                :id="'city'"
                                                                ref="city"
                                                                :error-messages="billingCityErrors()"
                                                                @input="$v.form.city.$touch()"
                                                                @blur="$v.form.city.$touch()"
                                                                required></v-text-field>
                                                        </v-flex>
                                                        <v-flex sm4>
                                                            <v-select
                                                                v-model="form.state"
                                                                ref="state"
                                                                label="State/Province"
                                                                name="state"
                                                                id="state"
                                                                :items="states"
                                                                item-text="state"
                                                                item-value="code"
                                                                :error-messages="billingStateErrors()"
                                                                @input="$v.form.state.$touch()"
                                                                @blur="$v.form.state.$touch()"
                                                                dense
                                                                autocomplete
                                                                required></v-select>
                                                        </v-flex>
                                                        <v-flex sm4>
                                                            <v-text-field
                                                                v-model="form.zip"
                                                                label="Zip/Postal Code"
                                                                name="zip"
                                                                id="zip"
                                                                ref="zip"
                                                                :error-messages="billingZipErrors()"
                                                                @input="$v.form.zip.$touch()"
                                                                @blur="$v.form.zip.$touch()"
                                                                required></v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout row wrap>
                                                        <v-flex sm5>
                                                            <v-select
                                                                v-model="form.country"
                                                                ref="country"
                                                                label="Country"
                                                                name="country"
                                                                id="country"
                                                                :items="countries"
                                                                item-text="country"
                                                                item-value="code"
                                                                :error-messages="billingCountryErrors()"
                                                                @input="$v.form.country.$touch()"
                                                                @blur="$v.form.country.$touch()"
                                                                dense
                                                                autocomplete
                                                                required></v-select>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout row wrap>
                                                        <v-flex sm6>
                                                            <v-text-field
                                                                v-model="form.phone"
                                                                label="Phone"
                                                                name="phone"
                                                                id="phone"
                                                                ref="phone"
                                                                mask="phone"
                                                                hint="+1 (808) 555-5555"
                                                                :error-messages="phoneErrors()"
                                                                @input="$v.form.phone.$touch()"
                                                                @blur="$v.form.phone.$touch()"
                                                                required></v-text-field>
                                                        </v-flex>
                                                        <v-flex sm6>
                                                            <v-text-field
                                                                v-model="form.fax"
                                                                label="FAX Number"
                                                                name="fax"
                                                                id="fax"
                                                                ref="fax"
                                                                mask="phone"
                                                                hint="+1 (808) 555-5555"></v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout row wrap>
                                                        <v-flex sm6>
                                                            <v-text-field
                                                                v-model="form.email"
                                                                label="Email"
                                                                name="email"
                                                                id="email"
                                                                ref="email"
                                                                :error-messages="emailErrors()"
                                                                @input="delayTouch($v.form.email)"
                                                                @blur="delayTouch($v.form.email)"
                                                                required></v-text-field>
                                                        </v-flex>
                                                        <v-flex sm6>
                                                            <v-text-field
                                                                v-model="form.email_confirm"
                                                                label="Confirm Email"
                                                                name="email_confirm"
                                                                id="email_confirm"
                                                                :error-messages="emailConfirmErrors()"
                                                                @input="delayTouch($v.form.email_confirm)"
                                                                @blur="delayTouch($v.form.email_confirm)"
                                                                required></v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout row wrap>
                                                        <v-flex sm12>
                                                            <v-checkbox
                                                                v-model="form.subscribe"
                                                                :value="true"
                                                                :true-value="true"
                                                                :false-value="false"
                                                                label="E-mail me travel deals and special offers from Panda Travel"
                                                                type="checkbox"></v-checkbox>
                                                        </v-flex>
                                                    </v-layout>

                                                    <v-divider></v-divider>

                                                    <h4 class="small-title primary--text text-thin">Payment Method</h4>

                                                    <v-layout row wrap>
                                                        <v-flex sm6>
                                                            <v-text-field
                                                                v-model="form.name"
                                                                label="Cardholder Name"
                                                                name="card_name"
                                                                id="card_name"
                                                                ref="card_name"
                                                                hint="Name on your credit card."
                                                                :error-messages="nameErrors()"
                                                                @input="$v.form.name.$touch()"
                                                                @blur="$v.form.name.$touch()"
                                                                required></v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-layout row wrap>
                                                        <v-flex sm6>
                                                            <v-text-field
                                                                v-model="form.card_number"
                                                                label="Card Number"
                                                                name="'card_number'"
                                                                id="'card_number'"
                                                                ref="card_number"
                                                                mask="credit-card"
                                                                :error-messages="cardNumberErrors()"
                                                                @input="delayTouch($v.form.card_number)"
                                                                @blur="delayTouch($v.form.card_number)"
                                                                required></v-text-field>
                                                        </v-flex>
                                                        <v-flex sm3>
                                                            <v-menu
                                                                ref="exp_menu"
                                                                lazy
                                                                :close-on-content-click="false"
                                                                v-model="exp_menu"
                                                                transition="scale-transition"
                                                                offset-y
                                                                full-width
                                                                :nudge-right="40"
                                                                min-width="290px">
                                                                <v-text-field
                                                                  v-model="form.card_expiration"
                                                                  slot="activator"
                                                                  name="card_expiration"
                                                                  label="Expires"
                                                                  hint="mm-yyyy"
                                                                  :error-messages="cardExpirationErrors()"
                                                                  @input="delayTouch($v.form.card_expiration)"
                                                                  @blur="delayTouch($v.form.card_expiration)"
                                                                  readonly
                                                                  required></v-text-field>
                                                                <v-date-picker
                                                                  type="month"
                                                                  v-model="form.card_expiration"
                                                                  ref="exp_picker"
                                                                  @change="exp_save"
                                                                  :min="exp_min"
                                                                  :max="exp_max"
                                                                  :show-current="false"
                                                                  date-format="MM-YYYY"
                                                                  no-title
                                                                  scrollable></v-date-picker>
                                                              </v-menu>
                                                        </v-flex>
                                                        <v-flex sm2>
                                                            <v-text-field
                                                                v-model="form.card_cvv"
                                                                label="CVV"
                                                                name="'card_cvv'"
                                                                id="'card_cvv'"
                                                                ref="card_cvv"
                                                                :error-messages="cardCvvErrors()"
                                                                @input="delayTouch($v.form.card_cvv)"
                                                                @blur="delayTouch($v.form.card_cvv)"
                                                                required></v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-flex>

                                                <v-flex class="pl-4" sm5>
                                                    <v-card class="package-details-card mt-4" flat outline>
                                                        <div class="card-header">
                                                            <h4 class="primary--text">Package Details</h4>
                                                        </div>
                                                        <v-list class="my-0" dense>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title>Itinerary Price</v-list-tile-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="text-xs-right">{{ subTotal | currency }}</v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            <v-list-tile v-if="form.insurance">
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title>Travel Insurance</v-list-tile-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="text-xs-right">{{ insurance | currency }}</v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            <v-list-tile v-if="form.discount">
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title>Discount ({{ package.discount.name }})</v-list-tile-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="text-xs-right">-{{ discount | currency }}</v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-list>
                                                        <v-divider class="mt-0"></v-divider>
                                                        <v-layout row wrap>
                                                            <v-flex class="info--text" sm12>
                                                                <v-alert color="info" :value="true" class="mx-3" outline>
                                                                    <v-switch label="Reserve with a Deposit" v-model="isDeposit" color="info"></v-switch>
                                                                    You can reserve this tour by making a deposit of {{ deposit | currency }} in total. The balance is due <strong>{{ balanceDuePeriod }}</strong> day(s) prior to your departure date. Deposits are non-refundable.
                                                                </v-alert>
                                                            </v-flex>
                                                        </v-layout>
                                                        <v-list class="my-0" dense>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title :class="[isDeposit ? 'grey--text text--lighten-1' : 'title primary--text']" class="text-xs-left">Total</v-list-tile-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title :class="[isDeposit ? 'grey--text text--lighten-1' : 'title primary--text']" class="text-xs-left text-xs-right">{{ total | currency }}</v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-list>
                                                        <v-list v-if="isDeposit" class="my-0" dense two-line>
                                                            <v-divider class="mt-0 mb-3"></v-divider>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="title primary--text">Deposit Price</v-list-tile-title>
                                                                    <v-list-tile-sub-title class="info--text text--lighten-4">Balance Due on {{ package.balance_at }}</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="title primary--text text-xs-right">{{ deposit | currency }}</v-list-tile-title>
                                                                    <v-list-tile-sub-title class="info--text text--lighten-4 text-sm-right">{{ balanceTotal | currency }}</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-list>
                                                        <v-divider class="mt-0"></v-divider>
                                                        <v-card-text class="pt-0">
                                                            <p class="caption blue-grey--text text--lighten-2 mb-0">* Taxes are included</p>
                                                            <p class="caption blue-grey--text text--lighten-2 mb-0">* Additional fees may apply. See our <a href="#" title="terms and conditions">terms and conditions</a> for details.</p>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-flex>

                                            </v-layout>

                                            <v-divider></v-divider>

                                            <v-layout row>
                                                <v-flex sm12>
                                                    <v-card class="card--flex-toolbar mx-1 my-1">
                                                        <v-toolbar card light dense>
                                                            <v-toolbar-title class="body-2 primary--text">Travel Package Terms &amp; Conditions</v-toolbar-title>
                                                            <v-spacer></v-spacer>
                                                        </v-toolbar>
                                                        <v-divider class="mt-0"></v-divider>
                                                        <v-card-text>
                                                            <div v-html="package.term.short_terms"></div>
                                                            <p>
                                                            Read the
                                                            <v-dialog v-model="full_terms" width="600px">
                                                                <a href="#" slot="activator" title="Full Terms & Conditions">Full Terms &amp; Conditions</a>
                                                                <v-card>
                                                                    <v-card-title>
                                                                        <span class="headline">Travel Package Terms &amp; Conditions</span>
                                                                    </v-card-title>
                                                                    <v-card-text v-html="package.term.terms"></v-card-text>
                                                                    <v-card-actions>
                                                                        <v-spacer></v-spacer>
                                                                        <v-btn color="orange darken-1" flat="flat" @click="form.agree_terms = false; full_terms = false">Disagree</v-btn>
                                                                        <v-btn color="green darken-1" flat="flat" @click="form.agree_terms = true; full_terms = false">Agree</v-btn>
                                                                    </v-card-actions>
                                                                </v-card>
                                                            </v-dialog>
                                                            </p>
                                                            <v-checkbox
                                                                v-model="form.agree_terms"
                                                                :value="true"
                                                                :true-value="true"
                                                                :false-value="false"
                                                                label="Agree to the Terms and Conditions"
                                                                type="checkbox"
                                                                :error-messages="agreeTermsErrors()"
                                                                @input="$v.form.agree_terms.$touch()"
                                                                @blur="$v.form.agree_terms.$touch()"
                                                                required></v-checkbox>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-flex>
                                            </v-layout>

                                        </v-container>

                                        <v-btn flat @click.native="step = 2">Previous</v-btn>
                                        <v-btn color="primary" @click.native="nextStep(4)">Continue</v-btn>
                                    </v-stepper-content>
                                    <v-stepper-content step="4">

                                        <v-container v-if="step === 4" grid-list-md fluid>

                                            <v-layout row wrap>
                                                <v-flex sm6>
                                                    <h2 class="headline primary--text text-uppercase">Order Confirmation</h2>
                                                </v-flex>
                                                <v-flex sm6>
                                                    <div v-if="isDeposit">
                                                        <v-container class="text-xs-right mx-auto pr-0">Order Total: <strong class="title">{{ deposit | currency }}</strong> <v-btn type="submit" color="success" class="ml-4">Reserve Package Now</v-btn></v-container>
                                                    </div>
                                                    <div v-else>
                                                        <v-container class="text-xs-right mx-auto pr-0">Order Total: <strong class="title">{{ total | currency }}</strong> <v-btn type="submit" color="success" class="ml-4">Purchase Package Now</v-btn></v-container>
                                                    </div>
                                                </v-flex>
                                            </v-layout>

                                            <v-layout row wrap>
                                                <v-flex sm12>
                                                    <!-- order-info component -->
                                                    <order-info :order="form"></order-info>

                                                </v-flex>
                                            </v-layout>

                                            <v-layout class="pt-3" row wrap>
                                                <v-flex xs12 sm7>
                                                    <v-card dense>
                                                        <v-card-media src="<?php echo $packageBannerUrl; ?>" height="160px">
                                                            <v-layout column class="media">
                                                                <v-card-title class="white--text pl-5 pt-5">
                                                                    <div class="pl-5">
                                                                        <div class="small-title pl-5 pt-5">{{ package.name }}</div>
                                                                    </div>
                                                                </v-card-title>
                                                            </v-layout>
                                                        </v-card-media>
                                                        <v-list class="mt-0" dense>
                                                            <template v-for="(room, roomIndex) in form.rooms">
                                                                <v-subheader>Room {{ roomIndex + 1 }}</v-subheader>
                                                                <template v-for="(traveler, travelerIndex) in room.travelers">
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>{{ traveler.first_name }} {{ traveler.middle_name }} {{ traveler.last_name }}</v-list-tile-title>
                                                                        </v-list-tile-content>
                                                                        <v-list-tile-action>
                                                                            <v-list-tile-action-text>(<span v-if="traveler.adult">Adult</span><span v-else>Child</span> {{ traveler.birthdate | age }} yrs) &nbsp; {{ traveler.birthdate | dateFormat }}</v-list-tile-action-text>
                                                                        </v-list-tile-action>
                                                                    </v-list-tile>

                                                                    <v-divider v-if="travelerIndex + 1 < room.travelers.length" class="my-0"></v-divider>
                                                                    <v-divider v-if="roomIndex + 1 != form.rooms.length && travelerIndex + 1 == room.travelers.length" class="my-0"></v-divider>
                                                                </template>
                                                            </template>
                                                        </v-list>
                                                    </v-card>
                                                </v-flex>
                                                <v-flex class="pl-4" sm5>
                                                    <v-card class="package-details-card" flat outline>
                                                        <div class="card-header">
                                                            <h4 class="primary--text">Package Details</h4>
                                                        </div>
                                                        <v-list class="my-0" dense>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title>Itinerary Price</v-list-tile-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="text-xs-right">{{ subTotal | currency }}</v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            <v-list-tile v-if="form.insurance">
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title>Travel Insurance</v-list-tile-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="text-xs-right">{{ insurance | currency }}</v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                            <v-list-tile v-if="form.discount">
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title>Discount ({{ package.discount.name }})</v-list-tile-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="text-xs-right">-{{ discount | currency }}</v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-list>
                                                        <v-divider class="my-0"></v-divider>
                                                        <v-list class="my-0" dense>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title :class="[isDeposit ? 'grey--text text--lighten-1' : 'title primary--text']" class="text-xs-left">Total</v-list-tile-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title :class="[isDeposit ? 'grey--text text--lighten-1' : 'title primary--text']" class="text-xs-left text-xs-right">{{ total | currency }}</v-list-tile-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-list>
                                                        <v-list v-if="isDeposit" class="my-0" dense two-line>
                                                            <v-divider class="mt-0 mb-3"></v-divider>
                                                            <v-list-tile>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="title primary--text">Deposit Price</v-list-tile-title>
                                                                    <v-list-tile-sub-title class="info--text text--lighten-4">Balance Due on {{ package.balance_at }}</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title class="title primary--text text-xs-right">{{ deposit | currency }}</v-list-tile-title>
                                                                    <v-list-tile-sub-title class="info--text text--lighten-4 text-sm-right">{{ balanceTotal | currency }}</v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </v-list-tile>
                                                        </v-list>
                                                        <v-divider class="mt-0"></v-divider>
                                                        <v-card-text class="pt-0">
                                                            <p class="caption blue-grey--text text--lighten-2 mb-0">* Taxes are included</p>
                                                            <p class="caption blue-grey--text text--lighten-2 mb-0">* Additional fees may apply. See our <a href="#" title="terms and conditions">terms and conditions</a> for details.</p>
                                                        </v-card-text>
                                                    </v-card>
                                                    <v-layout row wrap>
                                                        <v-flex sm12>
                                                            <div v-if="isDeposit">
                                                                <div class="text-xs-right mx-auto pr-0 mr-0"><v-btn type="submit" color="success" block>Reserve Package Now</v-btn></div>
                                                            </div>
                                                            <div v-else>
                                                                <div class="text-xs-right mx-auto pr-0 mr-0"><v-btn type="submit" color="success" block>Purchase Package Now</v-btn></div>
                                                            </div>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-flex>
                                            </v-layout>

                                            <v-divider></v-divider>

                                        </v-container>

                                        <v-btn flat @click.native="step = 3">Back</v-btn>
                                    </v-stepper-content>
                                </v-stepper-items>
                            </v-stepper>
                        </v-form>
                    </div>
                    <div class="pa-4" v-else>
                        <v-layout row wrap>
                            <v-flex sm12>
                                <v-alert type="success" :value="success" color="success" class="mb-3" outline>
                                    <h3 class="title ml-3 mt-0"> Tour Package successfully purchased</h3>
                                    <p class="ml-3 mb-0">Thank you for joining Panda Travel on this tour.</p>
                                </v-alert>

                                <v-card color="grey lighten-5">
                                    <v-container>
                                        <v-layout row wrap>
                                            <v-flex class="pr-5" sm6>
                                                <h4 class="small-title primary--text text-thin my-2">Your Information</h4>
                                                <v-divider></v-divider>
                                                <p class="mb-2"><strong>{{ form.name }}</strong></p>
                                                <p class="mb-0"><font-awesome-icon icon="phone"></font-awesome-icon> {{ form.phone }}</p>
                                                <p v-if="form.fax" class="mb-0"><font-awesome-icon icon="fax"></font-awesome-icon> {{ form.fax }}</p>
                                                <p class="mb-0"><font-awesome-icon icon="envelope"></font-awesome-icon> {{ form.email }}</p>
                                            </v-flex>
                                            <v-flex class="pl-5" sm6>
                                                <h4 class="small-title primary--text text-thin my-2">Billing Address</h4>
                                                <v-divider></v-divider>
                                                <p class="mb-2"><strong>{{ form.name }}</strong></p>
                                                <p class="mb-0"><font-awesome-icon icon="map-marker-alt"></font-awesome-icon> {{ form.address }}</p>
                                                <p class="mb-0" v-if="form.address2">{{ form.address2 }}</p>
                                                <p class="mb-0">{{ form.city }}, {{ form.state }}., {{ form.zip }} {{ form.country }} </p>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap>
                                            <v-flex class="pr-5" sm6>
                                                <h4 class="small-title primary--text text-thin my-2">Payment</h4>
                                                <v-divider></v-divider>
                                                <p class="mb-0">
                                                    <strong>{{ cardNiceType }}</strong>
                                                </p>
                                                <p>
                                                    <v-chip>
                                                        <font-awesome-icon :icon="[cardIcon.fa, cardIcon.card]" size="2x"></font-awesome-icon> &nbsp;
                                                         {{ form.card_number | creditCardMask }}
                                                    </v-chip>
                                                </p>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>

                            </v-flex>
                        </v-layout>

                        <v-layout class="pt-3" row wrap>
                            <v-flex xs12 sm7>
                                <v-card class="package-details-card" flat dense>
                                    <div class="card-header">
                                        <h4 class="primary--text">Traveler Information</h4>
                                    </div>
                                    <v-list class="mt-0" dense two-line>
                                        <template v-for="(room, roomIndex) in form.rooms">
                                            <v-subheader>Room {{ roomIndex + 1 }}</v-subheader>
                                            <template v-for="(traveler, travelerIndex) in room.travelers">
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title>{{ traveler.first_name }} {{ traveler.middle_name }} {{ traveler.last_name }}</v-list-tile-title>
                                                        <v-list-tile-sub-title>(<span v-if="traveler.adult">Adult</span><span v-else>Child</span> {{ traveler.birthdate | age }} yrs) &nbsp; {{ traveler.birthdate | dateFormat }}</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title>Frequent Flyer Number</v-list-tile-title>
                                                        <v-list-tile-sub-title>{{ traveler.ffp }}</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title>Seat Preference</v-list-tile-title>
                                                        <v-list-tile-sub-title>{{ traveler.seat_preference }}</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                    <v-list-tile-action>
                                                        <v-list-tile-action-text></v-list-tile-action-text>
                                                    </v-list-tile-action>
                                                </v-list-tile>
                                                <v-divider v-if="travelerIndex + 1 < room.travelers.length" class="my-0"></v-divider>
                                                <v-divider v-if="roomIndex + 1 != form.rooms.length && travelerIndex + 1 == room.travelers.length" class="my-0"></v-divider>
                                            </template>
                                        </template>
                                    </v-list>
                                </v-card>
                            </v-flex>
                            <v-flex class="pl-4" sm5>
                                <v-card class="package-details-card" flat outline>
                                    <div class="card-header">
                                        <h4 class="primary--text">Package Details</h4>
                                    </div>
                                    <v-list class="my-0" dense>
                                        <v-list-tile>
                                            <v-list-tile-content>
                                                <v-list-tile-title>Itinerary Price</v-list-tile-title>
                                            </v-list-tile-content>
                                            <v-list-tile-content>
                                                <v-list-tile-title class="text-xs-right">{{ subTotal | currency }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile v-if="form.insurance">
                                            <v-list-tile-content>
                                                <v-list-tile-title>Travel Insurance</v-list-tile-title>
                                            </v-list-tile-content>
                                            <v-list-tile-content>
                                                <v-list-tile-title class="text-xs-right">{{ insurance | currency }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile v-if="form.discount">
                                            <v-list-tile-content>
                                                <v-list-tile-title>Discount ({{ package.discount.name }})</v-list-tile-title>
                                            </v-list-tile-content>
                                            <v-list-tile-content>
                                                <v-list-tile-title class="text-xs-right">-{{ discount | currency }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                    <v-divider class="my-0"></v-divider>
                                    <v-list class="my-0" dense>
                                        <v-list-tile>
                                            <v-list-tile-content>
                                                <v-list-tile-title :class="[isDeposit ? 'grey--text text--lighten-1' : 'title primary--text']" class="text-xs-left">Total</v-list-tile-title>
                                            </v-list-tile-content>
                                            <v-list-tile-content>
                                                <v-list-tile-title :class="[isDeposit ? 'grey--text text--lighten-1' : 'title primary--text']" class="text-xs-left text-xs-right">{{ total | currency }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                    <v-list v-if="isDeposit" class="my-0" dense two-line>
                                        <v-divider class="mt-0 mb-3"></v-divider>
                                        <v-list-tile>
                                            <v-list-tile-content>
                                                <v-list-tile-title class="title primary--text">Deposit Price</v-list-tile-title>
                                                <v-list-tile-sub-title class="info--text text--lighten-4">Balance Due on {{ package.balance_at }}</v-list-tile-sub-title>
                                            </v-list-tile-content>
                                            <v-list-tile-content>
                                                <v-list-tile-title class="title primary--text text-xs-right">{{ deposit | currency }}</v-list-tile-title>
                                                <v-list-tile-sub-title class="info--text text--lighten-4 text-sm-right">{{ balanceTotal | currency }}</v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                    <v-divider class="mt-0"></v-divider>
                                    <v-card-text class="pt-0">
                                        <p class="caption blue-grey--text text--lighten-2 mb-0">* Taxes are included</p>
                                        <p class="caption blue-grey--text text--lighten-2 mb-0">* Additional fees may apply. See our <a href="#" title="terms and conditions">terms and conditions</a> for details.</p>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                        </v-layout>

                        <v-divider></v-divider>
                    </div>
                </booking-form>

            </v-card>

        </v-flex>
    </v-layout>

</article><!-- #post-## -->
