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
                </v-card-text>

                <booking-form endpoint="package" inline-template>
                    <v-form action="/wp-json/ptpkg/v1/package" @submit.prevent="onSubmit" method="post">
                        <v-stepper v-model="step">
                            <v-stepper-header>
                                <v-stepper-step step="1" :complete="step > 1" editable>Select Tour Package</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step step="2" :complete="step > 2">Review Tour</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step step="3" :complete="step > 3">Purchase Tour</v-stepper-step>
                                <v-divider></v-divider>
                                <v-stepper-step step="4">Confirmation</v-stepper-step>
                            </v-stepper-header>
                            <v-stepper-items>

                                <v-stepper-content step="1">

                                    <v-text-field id="form-name" label="Name" name="user_name" v-model="form.user_name" required></v-text-field>
                                    <v-text-field id="form-email" label="Email" name="email" v-model="form.email" required></v-text-field>
                                    <pre>{{ form }}</pre>
                                    <v-btn color="primary" @click.native="step = 2">Continue</v-btn>
                                    <v-btn color="primary" type="submit">Submit</v-btn>

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
                                    <v-btn color="primary" @click.native="step = 1">Submit</v-btn>
                                </v-stepper-content>
                            </v-stepper-items>
                        </v-stepper>
                    </v-form>
                </booking-form>

            </v-card>

        </v-flex>
    </v-layout>

</article><!-- #post-## -->
