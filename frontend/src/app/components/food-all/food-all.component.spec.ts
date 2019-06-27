import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FoodAllComponent } from './food-all.component';

describe('FoodAllComponent', () => {
  let component: FoodAllComponent;
  let fixture: ComponentFixture<FoodAllComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FoodAllComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FoodAllComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
