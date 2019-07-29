import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

import { FoodService } from '../../services/food.service';

import { UserService } from '../../services/user.service';

import {isUndefined} from "util";

@Component({
  selector: 'app-food-all',
  templateUrl: './food-all.component.html',
  providers: [FoodService]
})
export class FoodAllComponent implements OnInit {

  public foods: any = [];

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
    if (isUndefined(this._userService.getIdentity())) {
      this._router.navigate(["login"]);
    } else {
        this.getFoods();
    }
  }

  getFoods(){
    this._foodService.getFoods().subscribe(
        response => {
          this.foods = response;
        },
        error => {
          console.log(<any> error);
        }
    );
  }

  deleteFood(id){
    this._foodService.deleteFood(id).subscribe(
        response => {
          this.getFoods();

        },
        error => {

        }
    );
  }

    useLanguage(language: string) {
        this._translate.use(language);
    }
}
