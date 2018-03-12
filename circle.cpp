#include <GL/gl.h>
#include <GL/glu.h>
#include <GL/glut.h>
#include<iostream>

#define X 600
#define Y 600

int x0, y0, r;

void display(void)
{
    glClear(GL_COLOR_BUFFER_BIT);

    glColor3f(0.0, 1.0, 0.0);

    glBegin(GL_LINES);
    glVertex3i(-X+1, 0, 0);
    glVertex3i(X-1, 0, 0);
    glVertex3i(0, -Y+1, 0);
    glVertex3i(0, Y-1, 0);
    glEnd();

    int x = 0, y = r-1;
    int h0 = 1 - r;

    glBegin(GL_POINTS);

    while(y>=x) {
        std::cout << "(x: " << x << " y:" << y << " h: " <<  h0<<")---";
        glVertex3i(x0 + x, y0 + y, 0);
        glVertex3i(x0 + y, y0 + x, 0);
        glVertex3i(x0 - y, y0 + x, 0);
        glVertex3i(x0 - x, y0 + y, 0);
        glVertex3i(x0 - x, y0 - y, 0);
        glVertex3i(x0 - y, y0 - x, 0);
        glVertex3i(x0 + y, y0 - x, 0);
        glVertex3i(x0 + x, y0 - y, 0);
        if (h0 <= 0) {
            h0 += ((2 * x) + 3);
        } else {
            h0 += ((2 * (x - y)) + 5);
            y--;
        }
        x++;
    }

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
    std::cout << "X0: ";
    std::cin >> x0;
    std::cout << "Y0: ";
    std::cin >> y0;
    std::cout << "R: ";
    std::cin >> r;

    glutInit(&argc, argv);
    glutInitDisplayMode(GLUT_SINGLE | GLUT_RGB);
    glutInitWindowSize(X, Y);
    glutInitWindowPosition(300, 300);
    glutCreateWindow("Circle");
    init();
    glutDisplayFunc(display);
    glutMainLoop();

    return 0;
}

