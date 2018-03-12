#include <GL/gl.h>
#include <GL/glu.h>
#include <GL/glut.h>
#include<iostream>
#include <cmath>

#define SIN(x) sin(x * 3.1416/180)
#define COS(x) cos(x * 3.1416/180)

#define X 600
#define Y 600

int tm[3][2];
int tx, ty;
double sx, sy;
double r_f;

void translate(int _tx, int _ty) {
    for(int i=0; i<3; ++i) {
        tm[i][0] += _tx;
        tm[i][1] += _ty;
        std::cout << "Translating: " << tm[i][0] << " " << tm[i][1] << std::endl;
    }
}
void scale() {
    for(int i=0; i<3; ++i) {
        tm[i][0] = round(tm[i][0] * sx);
        tm[i][1] = round(tm[i][1] * sy);
    }
}
void rot(double degree) {
    for(int i=0; i<3; ++i) {
        int x = tm[i][0];
        int y = tm[i][1];
        tm[i][0] = round( (x * (COS(degree))) - (y * (SIN(degree))) );
        tm[i][1] = round( (x * (SIN(degree))) + (y * (COS(degree))) );
    }
}

void display(void)
{
    glClear(GL_COLOR_BUFFER_BIT);
    glEnable(GL_BLEND);

    glColor3f(0.0, 1.0, 1.0);

    glBegin(GL_LINES);
    glVertex3i(-X+1, 0, 0);
    glVertex3i(X-1, 0, 0);
    glVertex3i(0, -Y+1, 0);
    glVertex3i(0, Y-1, 0);
    glEnd();

    glColor4f(0.0, 1.0, 0.0, 0.5);
    glBegin(GL_TRIANGLES);
    glVertex3i(tm[0][0], tm[0][1], 0);
    glVertex3i(tm[1][0], tm[1][1], 0);
    glVertex3i(tm[2][0], tm[2][1], 0);
    glEnd();

    /*
    translate(tx, ty);
    glColor4f(0.0, 0.0, 1.0, 0.5);
    glBegin(GL_TRIANGLES);
    glVertex3i(tm[0][0], tm[0][1], 0);
    glVertex3i(tm[1][0], tm[1][1], 0);
    glVertex3i(tm[2][0], tm[2][1], 0);
    glEnd();
    translate(-tx, -ty);
    */

    int _tx = -tm[0][0], _ty = -tm[0][1];
    translate(_tx, _ty);
    //scale();
    //rot(90.0d);
    rot((double)r_f);
    translate(-_tx, -_ty);
    glColor4f(1.0, 0.0, 0.0, 0.5);
    glBegin(GL_TRIANGLES);
    glVertex3i(tm[0][0], tm[0][1], 0);
    glVertex3i(tm[1][0], tm[1][1], 0);
    glVertex3i(tm[2][0], tm[2][1], 0);
    glEnd();

    glFlush();
}
void init(void)
{
    glClearColor(0.0, 0.0, 0.0, 0.0);
    glMatrixMode(GL_PROJECTION);
    glLoadIdentity();
    gluOrtho2D(-X, X, -Y, Y);
}

int main(int argc, char** argv)
{
    for(int i = 0; i < 3; ++i) {
        std::cout << "Co-ordinate " << i+1 << ": x=";
        std::cin >> tm[i][0];
        std::cout << "Co-ordinate " << i+1 << ": y=";
        std::cin >> tm[i][1];
    }
    /*std::cout << "Tx: ";
    std::cin >> tx;
    std::cout << "Ty: ";
    std::cin >> ty;
    std::cout << "Sx: ";
    std::cin >> sx;
    std::cout << "Sy: ";
    std::cin >> sy;*/
    std::cout << "Degrees to Rotate: ";
    std::cin >> r_f;

    glutInit(&argc, argv);
    glutInitDisplayMode(GLUT_SINGLE | GLUT_RGB);
    glutInitWindowSize(X, Y);
    glutInitWindowPosition(300, 300);
    glutCreateWindow("Triangle");
    init();
    glutDisplayFunc(display);
    glBlendFunc(GL_SRC_ALPHA, GL_ONE_MINUS_SRC_ALPHA);
    glutMainLoop();

    return 0;
}


