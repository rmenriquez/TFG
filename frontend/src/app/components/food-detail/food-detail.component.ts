import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

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
      private _userService: UserService,
      private  _translate: TranslateService
  ) {
    _translate.setDefaultLang('es');
  }

  ngOnInit() {
    this.getFood();
  }

  getFood(){
     this._route.params.subscribe(params =>{
      let id_food = params['id'];
      this._foodService.viewFood(id_food).subscribe(
        response => {
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

  useLanguage(language: string) {
    this._translate.use(language);
  }

}
