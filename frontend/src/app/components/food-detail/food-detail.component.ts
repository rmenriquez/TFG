import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FoodService } from '../../services/food.service';
import { Food } from '../../models/food';
import { UserService } from '../../services/user.service';
import {isUndefined} from "util";

@Component({
  selector: 'app-food-detail',
  templateUrl: './food-detail.component.html',
  providers: [UserService, FoodService]
})
export class FoodDetailComponent implements OnInit {

  public food: Food;

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _foodService: FoodService,
      private _userService: UserService
  ) {

  }

  ngOnInit() {
    this.getFood();
  }

  getFood(){
     this._route.params.subscribe(params =>{
      console.log(params);
      let id_food = params['id'];
      console.log(id_food);
      this._foodService.viewFood(id_food).subscribe(
        response => {
          //console.log(response['allergens']);

            //console.log('estoy dentro');
            this.food = response;
            if(isUndefined(this.food)){
              this._router.navigate(['allFoods']);
            }

        },
          error => {
          console.log(<any> error);
            this._router.navigate(['allFoods']);
          }
      );
    });
  }

}
