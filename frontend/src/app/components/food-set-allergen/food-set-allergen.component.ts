/**
 * Created by RaquelMarcos on 10/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../../services/user.service';
import { Food } from '../../models/food';
import { FoodService } from '../../services/food.service';
import { AllergenService } from '../../services/allergen.service';
import {isUndefined} from "util";


@Component({
    selector: 'app-food-set-allergen',
    templateUrl: './food-set-allergen.component.html',
    providers: [UserService, FoodService]
})

export class FoodSetAllergen implements OnInit{
    private page_title: string;
    private identity;
    private food: Food;
    private status: string;

    private allergens: any = [];
    private errors = {};

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService,
        private _foodService: FoodService,
        private _allergenService: AllergenService
    ){
        this.identity = this._userService.getIdentity();
    }

    ngOnInit(){
        if(this.identity == null){
            this._router.navigate(["/login"]);
        }
        this._route.params.subscribe(params => {
            console.log(params);
            let id_food = params['id'];
            this.getFood(id_food);
        });
    }

    onSubmit(form){
        console.log(form.value);
        /*this._foodService.setFoodAllergens().subscribe(
            response => {
                console.log(response);
            },
            error => {
                console.log(<any> error);
            }
        );*/
    }

   /* getFood(id_food){
        this._foodService.viewFood(id_food).subscribe(
            response => {
                this._allergenService.getAllergens().subscribe(
                    response => {
                        //console.log(response);
                        this.allergens = response;
                        console.log(this.allergens);
                        //console.log(this.allergens[0]);
                    },
                    error => {
                        console.log(<any> error);
                    }
                );
                console.log(response);

                //console.log('estoy dentro');
                this.food = response;
                this.page_title = 'Set allergens to ' + this.food.title;

                if(isUndefined(this.food)){
                    this._router.navigate(['allFoods']);
                }

            },
            error => {
                console.log(<any> error);
                this.errors = error.error;
                this._router.navigate(['allFoods']);
            }
        );
    }*/
   getFood(id_food){
       this._allergenService.getAllergens().subscribe(
           response => {
               this.allergens = response;
               console.log(this.allergens);
               console.log(this.allergens[0].id_allergen);
               this._foodService.viewFood(id_food).subscribe(
                   response => {
                       console.log(response);
                       this.food = response;
                       this.page_title = 'Set allergens to ' + this.food.title;
                       if(isUndefined(this.food)){
                           this._router.navigate(['allFoods']);
                       }
               },
                   error => {
                       console.log(<any> error);
                   }
               );
           },
           error => {
               console.log(<any> error);
           }
       );
   }

}