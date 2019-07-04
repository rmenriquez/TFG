import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FoodService } from '../../services/food.service';
import { Food } from '../../models/food';
import { UserService } from '../../services/user.service';
import {isUndefined} from "util";

@Component({
  selector: 'app-food-edit',
  templateUrl: './food-edit.component.html',
  providers: [UserService, FoodService]
})
export class FoodEditComponent implements OnInit {

  public page_title: string;
  public status: string;

  public food: Food;

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _foodService: FoodService,
      private _userService: UserService
  ) {

  }

  ngOnInit() {
    this._route.params.subscribe(params => {
      console.log(params);
      let id_food = params['id'];
      this.getFood(id_food);
    });
  }

  getFood(id_food){
      this._foodService.viewFood(id_food).subscribe(
          response => {
            console.log(response);

            //console.log('estoy dentro');
            this.food = response;
            this.page_title = 'Editar ' + this.food.title;
            if(isUndefined(this.food)){
              this._router.navigate(['allFoods']);
            }

          },
          error => {
            console.log(<any> error);
            this._router.navigate(['allFoods']);
          }
      );
  }

  onSubmit(form){
    //Servicio
    console.log(this.food.id_food);
    this._foodService.updateFood(this.food, this.food.id_food).subscribe(
        response => {
          if(response == null){
            this.status = 'success';
            this._router.navigate(['/allFoods']);
          }
          console.log(this.status);

        },
        error => {
          console.log(<any> error);
          this.status = 'error';
        }
    );

  }

}
