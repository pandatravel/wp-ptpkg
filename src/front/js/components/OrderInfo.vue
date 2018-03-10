<template>
    <v-card color="grey lighten-5">
        <v-container>
            <v-layout row wrap>
                <v-flex class="pr-5" sm6>
                    <h4 class="small-title primary--text text-thin my-2">Your Information</h4>
                    <v-divider></v-divider>
                    <p class="mb-2"><strong>{{ order.name }}</strong></p>
                    <p class="mb-0"><font-awesome-icon icon="phone"></font-awesome-icon> {{ order.phone }}</p>
                    <p v-if="order.fax" class="mb-0"><font-awesome-icon icon="fax"></font-awesome-icon> {{ order.fax }}</p>
                    <p class="mb-0"><font-awesome-icon icon="envelope"></font-awesome-icon> {{ order.email }}</p>
                </v-flex>
                <v-flex class="pl-5" sm6>
                    <h4 class="small-title primary--text text-thin my-2">Billing Address</h4>
                    <v-divider></v-divider>
                    <p class="mb-2"><strong>{{ order.name }}</strong></p>
                    <p class="mb-0"><font-awesome-icon icon="map-marker-alt"></font-awesome-icon> {{ order.address }}</p>
                    <p class="mb-0" v-if="order.address2">{{ order.address2 }}</p>
                    <p class="mb-0">{{ order.city }}, {{ order.state }}., {{ order.zip }} {{ order.country }} </p>
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
                             {{ order.card_number | creditCardMask }}
                        </v-chip>
                    </p>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>

<script>
import valid from 'card-validator'
import creditCards from '../mixins/creditCards'
import creditCardMask from '../mixins/creditCardMask'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import fontawesome from '@fortawesome/fontawesome'
import brands from '@fortawesome/fontawesome-free-brands'
import faAddressCard from '@fortawesome/fontawesome-free-solid/faAddressCard'
import faCreditCard from '@fortawesome/fontawesome-free-solid/faCreditCard'
import faPhone from '@fortawesome/fontawesome-free-solid/faPhone'
import faFax from '@fortawesome/fontawesome-free-solid/faFax'
import faEnvelope from '@fortawesome/fontawesome-free-solid/faEnvelope'
import faMapMarkerAlt from '@fortawesome/fontawesome-free-solid/faMapMarkerAlt'
fontawesome.library.add(brands, faAddressCard, faCreditCard, faPhone, faFax, faEnvelope, faMapMarkerAlt)

  export default {
    name: 'order-info',

    mixins: [
        creditCards,
        creditCardMask,
    ],

    components: {
        FontAwesomeIcon
    },

    props: ['order'],

    data() {
      return {

      }
    },

    computed: {
        cardIcon() {
            let number = valid.number(this.order.card_number)
            if (number.card) {
                let type = number.card.type
                let card = this.creditCards.filter(function(item) {
                    return item.card == type
                })
                if (card.length == 0) {
                    return {'card':'credit-card', 'fa': 'fas'}
                }
                return card[0].icon
            }
            return {'card':'credit-card', 'fa': 'fas'}
        },
        cardNiceType() {
            let number = valid.number(this.order.card_number)
            if (number.card) {
                return number.card.niceType
            }
        },
    },
  }
</script>
