import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FoodService } from '../../services/food.service';

@Component({
  selector: 'app-food-all',
  templateUrl: './food-all.component.html',
  providers: [FoodService]
})
export class FoodAllComponent implements OnInit {

  public title: string;
  public foods: any = [];

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _foodService: FoodService
  ) {
    this.title = 'Foods';
  }

  ngOnInit() {
    this._foodService.getFoods().subscribe(
        response => {
          //console.log(response);
          this.foods = response;
        },
        error => {
          console.log(<any> error);
        }
    );
  }

}
