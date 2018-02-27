<template>
    <form @submit.prevent="onSubmit" method="post">
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

                    <room v-for="(room, roomIndex) in form.rooms" :key="roomIndex" :room="form.rooms[roomIndex]" :index="roomIndex" :$v="$v" :room_max="room_max" :total_travelers="totalTravelers" :max_travelers="maxTravelers" @remove-room="removeRoom" @remove-traveler="removeTraveler"></room>

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
    </form>
</template>

<script>
// Mixins
import { validationMixin } from 'vuelidate'
import { required, minLength, maxLength, email } from 'vuelidate/lib/validators'
import errors from '../mixins/errors'
import form from '../mixins/form'
// Components
import room from '../components/Room.vue';

  export default {
    mixins: [
        validationMixin,
        errors,
        form,
    ],

    components: {
        room,
    },

    props: {
        'endpoint': {
            type: String,
            default: function() {
                return 'packages';
            }
        }
    },

    data() {
      return {
          form: new Form({
              tour_id: '',
              rate_id: '',
              insurance: false,
              subscribe: false,
              agree_terms: '',

              rooms: [],
          }),
          step:1,
          room_max: 3,
          submiting: false,
          package: window._ptpkgAPIDataPreload.data,
      }
    },

    validations: {
        form: {
            agree_terms: { required },
            rooms: {
                required,
                minLength: minLength(1),
                $each: {
                    travelers: {
                        required,
                        minLength: minLength(1),
                        $each: {
                            first_name: {required},
                            last_name: {required},
                            birthdate: {required},
                            gender: {required},
                        }
                    }
                }
            }
        }
    },

    created() {
        this.form.tour_id = this.package.id;
        this.room_max = this.package.room_max;

        this.addRoom();

        this.form.rate_id = this.rateId;
    },

    computed: {
        adults() {
            return this.form.rooms.reduce(function(adults, room) {
                return adults + room.travelers.reduce(function(adults, traveler) {
                    return (traveler.adult ? adults + 1 : adults);
                }, 0);
            }, 0);
        },
        children() {
            return this.form.rooms.reduce(function(children, room) {
                return children + room.travelers.reduce(function(children, traveler) {
                    return (! traveler.adult ? children + 1 : children);
                }, 0);
            }, 0);
        },
        totalTravelers() {
            return this.adults + this.children;
        },
        maxTravelers() {
            return this.package.rates.reduce(function(max, rate) {
                var total = rate.adult + rate.child;
                return (total > max ? total : max);
            }, 0);
        },
        rateTier() {
            if (this.totalTravelers == 0) {
                return [];
            }
            var rate = this.package.rates.filter(this.rateFilter(this.adults, this.children));
            return rate[0];
        },
        rateId() {
            return this.rateTier.id;
        },
        subTotal() {
            return Number(this.package.rates.reduce(this.rateReducer, 0));
        },
        total() {
            return this.subTotal + this.insurance;
        },
        perPersonRate() {
            if (this.totalTravelers == 0) {
                return 0;
            }
            return this.subTotal / this.totalTravelers;
        },
        premium() {
            if (this.totalTravelers == 0) {
                return [];
            }
            return this.package.insurance.premiums.filter(this.premiumFilter(this.perPersonRate));
        },
        premiumPrice() {
            return Number(this.package.insurance.premiums.reduce(this.premiumReducer, 0));
        },
        insurance() {
            return this.premiumPrice * this.totalTravelers;
        },
    },

    methods: {
        onSubmit() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                // this.$notify({ type: 'error', title: 'Error!', text: 'The form contains invalid fields.'});
                return false;
            } else {
                this.submiting = true;

                this.form.post(this.endpoint)
                         .then(data => {
                            this.submiting = false;
                            console.log(data)
                         })
                         .catch(errors => {
                            this.submiting = false;
                            console.log(errors)
                        });
            }
        },
        onSuccess(data) {
            this.submiting = false;
        },
        onFail(errors) {
            this.submiting = false;
            // var bag = this.$validator.errors;
            // Object.keys(errors).map(function(key) {
            //     var splitted = key.split('.', 2);
            //     // we assume that first dot divides column and locale (TODO maybe refactor this and make it more general)
            //     if (splitted.length > 1) {
            //         bag.add(splitted[0]+'_'+splitted[1], errors[key][0]);
            //     } else {
            //         bag.add(key, errors[key][0]);
            //     }
            // });
        },
        clear () {
            this.$v.$reset()
            this.form.reset()
        },
        addRoom() {
            var newRoom = this.form.rooms.push({travelers:[]}) -1;
            this.addAdult(newRoom);
        },
        removeRoom(roomIndex) {
            this.form.rooms.splice(roomIndex, 1)
        },
        addAdult(roomIndex) {
            this.addTraveler(roomIndex, true);
        },
        addChild(roomIndex) {
            this.addTraveler(roomIndex, false);
        },
        addTraveler(roomIndex, adult = true) {
            if (this.totalTravelers < this.maxTravelers) {
                if (this.hasVacancy(roomIndex)) {
                    this.form.rooms[roomIndex].travelers.push({
                        first_name:'',
                        middle_name:'',
                        last_name:'',
                        birthdate: null,
                        gender: '',
                        adult: adult,
                        ffp: '',
                        seat_preference: '',
                        country: '',
                        state: '',
                    });
                }
            }
        },
        removeTraveler(index) {
            this.form.rooms[index.room].travelers.splice(index.traveler, 1);
        },
        getRoomCount(roomIndex) {
            return this.form.rooms[roomIndex].travelers.length;
        },
        hasVacancy(roomIndex) {
            return this.getRoomCount(roomIndex) < this.room_max;
        },
        rateFilter(adults, children) {
            return function(rate) {
                return rate.adult == adults && rate.child == children;
            }
        },
        premiumFilter(perPersonRate) {
            return function(premium) {
                return perPersonRate >= premium.range_start && perPersonRate <=premium.range_end;
            }
        },
        rateReducer(price, rate) {
            if (rate.adult == this.adults && rate.child == this.children) {
                price = rate.price;
            }
            return price;
        },
        premiumReducer(price, premium) {
            if (this.perPersonRate >= premium.range_start && this.perPersonRate <= premium.range_end) {
                price = premium.price;
            }
            return price;
        },
    },
  }
</script>
